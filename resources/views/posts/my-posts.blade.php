{{--
    My Posts View

    User এর নিজের সব পোস্টের তালিকা দেখায়।
    সব status এর পোস্ট দেখাবে (draft, pending, published, rejected)।

    Variables:
    - $posts: Paginated collection of user's posts
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                আমার পোস্ট
            </h2>
            <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                নতুন পোস্ট
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($posts->isEmpty())
            <!-- No Posts Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <p class="text-gray-500 mb-4">আপনি এখনো কোনো পোস্ট লেখেননি।</p>
                    <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        প্রথম পোস্ট লিখুন
                    </a>
                </div>
            </div>
            @else
            <!-- Posts Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    শিরোনাম
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    অবস্থা
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    তৈরি
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    কাজ
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($posts as $post)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('posts.show', $post) }}" class="text-gray-900 font-medium hover:text-blue-600">
                                        {{ Str::limit($post->title, 50) }}
                                    </a>
                                    @if($post->isRejected() && $post->rejection_reason)
                                    <p class="text-xs text-red-600 mt-1">
                                        কারণ: {{ Str::limit($post->rejection_reason, 50) }}
                                    </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($post->status)
                                    @case('draft')
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">খসড়া</span>
                                    @break
                                    @case('pending')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">অপেক্ষমাণ</span>
                                    @break
                                    @case('published')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">প্রকাশিত</span>
                                    @break
                                    @case('rejected')
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">প্রত্যাখ্যাত</span>
                                    @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $post->created_at->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:text-blue-900">
                                            দেখুন
                                        </a>

                                        @can('update', $post)
                                        <a href="{{ route('posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900">
                                            সম্পাদনা
                                        </a>
                                        @endcan

                                        @can('submit', $post)
                                        <form action="{{ route('posts.submit', $post) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                জমা দিন
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
            @endif

        </div>
    </div>
</x-app-layout>