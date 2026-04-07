{{--
    Posts Index View (Public Blog Listing)

    সব published posts এর তালিকা দেখায়।
    Guest এবং logged-in users উভয়েই দেখতে পারবে।

    Variables:
    - $posts: Paginated collection of published posts
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ব্লগ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($posts->isEmpty())
            <!-- No Posts Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    এখনো কোনো পোস্ট প্রকাশিত হয়নি।
                </div>
            </div>
            @else
            <!-- Posts Grid -->
            <div class="grid gap-6">
                @foreach($posts as $post)
                <article class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Post Title -->
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="{{ route('posts.show', $post) }}" class="text-gray-900 hover:text-blue-600">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <!-- Post Meta -->
                        <div class="text-sm text-gray-500 mb-4">
                            <span>{{ $post->author->name }}</span>
                            <span class="mx-2">&bull;</span>
                            <span>{{ $post->published_at->format('d M, Y') }}</span>
                        </div>

                        <!-- Post Excerpt -->
                        <p class="text-gray-600 mb-4">
                            {{ Str::limit(strip_tags($post->body), 200) }}
                        </p>

                        <!-- Read More Link -->
                        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            আরও পড়ুন &rarr;
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
            @endif

        </div>
    </div>
</x-app-layout>