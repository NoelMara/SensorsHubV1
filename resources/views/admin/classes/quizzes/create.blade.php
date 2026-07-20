@extends('layouts.app')

@section('title', 'Add Quiz')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('admin.classes.quizzes.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Quizzes
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">Add Quiz</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Create a new quiz for {{ $class->name }}.</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.classes.quizzes.store', $class) }}" x-data="quizBuilder()">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Quiz Title</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('title') border-red-500 @enderror"
                            placeholder="e.g., Sensor Basics Quiz">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none"
                            placeholder="Brief description of the quiz...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Instructions</label>
                        <textarea name="instructions" id="instructions" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none"
                            placeholder="Instructions for students...">{{ old('instructions') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Points</label>
                            <input type="number" name="points" id="points" required min="1" value="{{ old('points', 20) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                        </div>
                        <div>
                            <label for="passing_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Passing Score (%)</label>
                            <input type="number" name="passing_score" id="passing_score" required min="0" max="100" value="{{ old('passing_score', 60) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Due Date <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" id="is_published" value="1" checked
                            class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <label for="is_published" class="text-sm text-gray-700 dark:text-gray-300">Publish immediately</label>
                    </div>

                    {{-- Questions Section --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-5">
                        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">Questions</h2>

                        <template x-for="(question, qIndex) in questions" :key="qIndex">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300" x-text="'Q' + (qIndex + 1)"></span>
                                    <button type="button" @click="removeQuestion(qIndex)" 
                                        class="text-red-500 hover:text-red-700 text-xs" x-show="questions.length > 1">
                                        <i class="fas fa-trash mr-1"></i> Remove
                                    </button>
                                </div>

                                <input type="text" :name="'questions[' + qIndex + '][question]'" required
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm mb-3"
                                    placeholder="Enter your question..." x-model="question.text">

                                <div class="space-y-2 mb-3">
                                    <template x-for="(option, oIndex) in question.options" :key="oIndex">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" :name="'questions[' + qIndex + '][correct_option]'" 
                                                :value="oIndex" :checked="option.isCorrect"
                                                @change="setCorrect(qIndex, oIndex)"
                                                class="h-4 w-4 text-primary focus:ring-primary flex-shrink-0">
                                            <input type="text" :name="'questions[' + qIndex + '][options][' + oIndex + '][text]'" required
                                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm"
                                                :placeholder="'Option ' + (oIndex + 1)" x-model="option.text">
                                            <input type="hidden" :name="'questions[' + qIndex + '][options][' + oIndex + '][is_correct]'" 
                                                :value="option.isCorrect ? '1' : '0'">
                                            <button type="button" @click="removeOption(qIndex, oIndex)" 
                                                class="text-red-400 hover:text-red-600 text-xs" x-show="question.options.length > 2">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" @click="addOption(qIndex)" 
                                    class="text-xs text-primary hover:underline" x-show="question.options.length < 6">
                                    <i class="fas fa-plus mr-1"></i> Add Option
                                </button>
                            </div>
                        </template>

                        <button type="button" @click="addQuestion()" 
                            class="w-full py-2.5 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-500 dark:text-gray-400 hover:border-primary hover:text-primary transition">
                            <i class="fas fa-plus mr-1"></i> Add Question
                        </button>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-save mr-1.5"></i> Save Quiz
                    </button>
                    <a href="{{ route('admin.classes.quizzes.index', $class) }}"
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function quizBuilder() {
        return {
            questions: [{
                text: '',
                options: [
                    { text: '', isCorrect: false },
                    { text: '', isCorrect: false },
                    { text: '', isCorrect: false },
                    { text: '', isCorrect: false },
                ]
            }],

            addQuestion() {
                this.questions.push({
                    text: '',
                    options: [
                        { text: '', isCorrect: false },
                        { text: '', isCorrect: false },
                        { text: '', isCorrect: false },
                        { text: '', isCorrect: false },
                    ]
                });
            },

            removeQuestion(index) {
                this.questions.splice(index, 1);
            },

            addOption(qIndex) {
                this.questions[qIndex].options.push({ text: '', isCorrect: false });
            },

            removeOption(qIndex, oIndex) {
                this.questions[qIndex].options.splice(oIndex, 1);
            },

            setCorrect(qIndex, oIndex) {
                this.questions[qIndex].options.forEach((opt, i) => {
                    opt.isCorrect = (i === oIndex);
                });
            }
        }
    }
</script>
@endpush