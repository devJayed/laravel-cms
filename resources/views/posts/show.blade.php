{{--
    Single Post View

    একটি পোস্টের বিস্তারিত দেখায়।
    Post এর status অনুযায়ী বিভিন্ন action buttons দেখাবে।

    Variables:
    - $post: The Post model instance
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>

            <!-- Status Badge -->
            @switch($post->status)
            @case('draft')
            <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-700">খসড়া</span>
            @break
            @case('pending')
            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700">অনুমোদনের অপেক্ষায়</span>
            @break
            @case('published')
            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700">প্রকাশিত</span>
            @break
            @case('rejected')
            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-700">প্রত্যাখ্যাত</span>
            @break
            @endswitch
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Rejection Reason Alert -->
            @if($post->isRejected() && $post->rejection_reason)
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">প্রত্যাখ্যানের কারণ:</h3>
                        <p class="text-sm text-red-700 mt-1">
                            {{ $post->rejection_reason }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Post Content -->
            <article class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Post Meta -->
                    <div class="text-sm text-gray-500 mb-6 flex items-center gap-4">
                        <span>লেখক: {{ $post->author->name }}</span>
                        @if($post->published_at)
                        <span>&bull;</span>
                        <span>প্রকাশ: {{ $post->published_at->format('d M, Y') }}</span>
                        @endif
                        <span>&bull;</span>
                        <span>তৈরি: {{ $post->created_at->format('d M, Y') }}</span>
                    </div>

                    <!-- Post Body -->
                    <div class="prose max-w-none text-gray-800">
                        {!! nl2br(e($post->body)) !!}
                    </div>
                </div>
            </article>

            <!-- Action Buttons -->
            @auth
            <div class="mt-6 flex flex-wrap gap-3">

                {{-- Author Actions --}}
                @can('update', $post)
                <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    সম্পাদনা করুন
                </a>
                @endcan

                @can('submit', $post)
                <form action="{{ route('posts.submit', $post) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                        অনুমোদনের জন্য জমা দিন
                    </button>
                </form>
                @endcan

                @can('delete', $post)
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই পোস্টটি মুছে ফেলতে চান?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                        মুছে ফেলুন
                    </button>
                </form>
                @endcan

                {{-- Editor Actions --}}
                @can('approve', $post)
                <form action="{{ route('posts.approve', $post) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                        অনুমোদন করুন
                    </button>
                </form>
                @endcan

                @can('reject', $post)
                <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                    প্রত্যাখ্যান করুন
                </button>
                @endcan

            </div>

            {{-- Reject Modal --}}
            @can('reject', $post)
            <div id="reject-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">প্রত্যাখ্যানের কারণ</h3>
                        <form action="{{ route('posts.reject', $post) }}" method="POST">
                            @csrf
                            <textarea
                                name="rejection_reason"
                                rows="4"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="কেন এই পোস্টটি প্রত্যাখ্যান করা হচ্ছে তা লিখুন..."></textarea>

                            <div class="mt-4 flex justify-end gap-3">
                                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                    বাতিল
                                </button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500">
                                    প্রত্যাখ্যান করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
            @endauth

            <!-- Back Link -->
            <div class="mt-6">
                <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-900">
                    &larr; ফিরে যান
                </a>
            </div>

        </div>
    </div>
</x-app-layout>