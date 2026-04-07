{{--
    Create Post View

    নতুন পোস্ট তৈরি করার ফর্ম।
    নতুন পোস্ট সবসময় 'draft' status এ তৈরি হয়।

    Teaching Points:
    - Form validation with @error directive
    - CSRF protection with @csrf
    - old() helper for form repopulation
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            নতুন পোস্ট লিখুন
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Form - POST method এ posts.store route এ submit হবে --}}
                    <form action="{{ route('posts.store') }}" method="POST">
                        {{-- CSRF Token - Laravel এর security feature --}}
                        @csrf

                        <!-- Title Field -->
                        <div class="mb-6">
                            <x-input-label for="title" value="শিরোনাম" />
                            <x-text-input
                                id="title"
                                name="title"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('title')"
                                required
                                autofocus
                                placeholder="পোস্টের শিরোনাম লিখুন" />
                            {{-- Validation error message --}}
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Body Field -->
                        <div class="mb-6">
                            <x-input-label for="body" value="বিস্তারিত" />
                            <textarea
                                id="body"
                                name="body"
                                rows="12"
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="পোস্টের বিস্তারিত লিখুন...">{{ old('body') }}</textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <!-- Info Text -->
                        <div class="mb-6 text-sm text-gray-500">
                            <p>
                                <strong>টিপস:</strong> পোস্ট প্রথমে 'খসড়া' হিসেবে সংরক্ষিত হবে।
                                পরে আপনি এটি সম্পাদনা করতে পারবেন এবং যখন প্রস্তুত হবে তখন অনুমোদনের জন্য জমা দিতে পারবেন।
                            </p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                খসড়া সংরক্ষণ করুন
                            </x-primary-button>

                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
                                বাতিল
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>