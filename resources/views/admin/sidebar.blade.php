                <a href="{{ route('admin.mcqs.index') }}" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                    <i class="fas fa-question-circle mr-3"></i>MCQs
                </a>
                <a href="{{ route('admin.mock-exams.index') }}" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                    <i class="fas fa-clipboard-list mr-3"></i>Mock Tests
                </a>

                <!-- Mock Exam Management -->
                <div class="py-2">
                    <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Mock Exam Management</h3>
                    <a href="{{ route('admin.mock-exams.index') }}" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                        <i class="fas fa-graduation-cap mr-3"></i>Exams
                    </a>
                    <a href="{{ route('admin.mock-exam-papers.index') }}" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                        <i class="fas fa-file-alt mr-3"></i>Papers
                    </a>
                    <a href="{{ route('admin.question-bank') }}" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                        <i class="fas fa-book mr-3"></i>Question Bank
                    </a>
                </div>
=======
                <a href="{{ route('admin.mcqs.index') }}" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                    <i class="fas fa-question-circle mr-3"></i>MCQs
                </a>
                <a href="{{ route('admin.mock-tests.index') }}" class="block py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                    <i class="fas fa-clipboard-list mr-3"></i>Mock Tests
                </a>
