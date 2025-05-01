<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Quiz Challenge</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen p-8" style="border: 15px solid #ECE852">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4" style="color: #73EC8B">Math Quiz Challenge</h1>
            <div class="flex items-center justify-center gap-4">
                <span class="px-4 py-2 rounded-full bg-gray-100">Easy Level</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Question</span>
                    <span class="font-bold" style="color: #ECE852" id="questionCounter">1/6</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="h-3 bg-gray-200 rounded-full mb-8">
            <div class="h-full rounded-full transition-all duration-300" id="progressBar" style="width: 16.66%; background: #73EC8B"></div>
        </div>

        <!-- Questions Container -->
        <div id="questionsContainer">
            <!-- Question 1 -->
            <div class="question-card bg-white rounded-xl shadow-lg p-8 mb-8 border-4" style="border-color: #ECE852">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">A school needs to select three students from a group of five to form a cleanup committee. The order they are chosen doesn't matter. How many different cleanup committees are possible? Is this a permutation or combination problem?</h2>
                    <div class="space-y-4" id="answerOptions">
                        <div class="answer-option flex items-center gap-3 p-4 rounded-lg border hover:border-green-400 cursor-pointer">
                            <input type="radio" name="answer" class="h-5 w-5" id="option1" value="combination_5C3=10">
                            <label for="option1" class="flex-1">Combination problem, 5C3 = 10</label>
                        </div>
                        <div class="answer-option flex items-center gap-3 p-4 rounded-lg border hover:border-green-400 cursor-pointer">
                            <input type="radio" name="answer" class="h-5 w-5" id="option2" value="permutation_5P3=60">
                            <label for="option2" class="flex-1">Permutation problem, 5P3 = 60</label>
                        </div>
                        <div class="answer-option flex items-center gap-3 p-4 rounded-lg border hover:border-green-400 cursor-pointer">
                            <input type="radio" name="answer" class="h-5 w-5" id="option3" value="combination_5C2=10">
                            <label for="option3" class="flex-1">Combination problem, 5C2 = 10</label>
                        </div>
                        <div class="answer-option flex items-center gap-3 p-4 rounded-lg border hover:border-green-400 cursor-pointer">
                            <input type="radio" name="answer" class="h-5 w-5" id="option4" value="permutation_5P2=20">
                            <label for="option4" class="flex-1">Permutation problem, 5P2 = 20</label>
                        </div>
                    </div>
                    <div class="error-message text-red-600 mt-2 hidden">Please select an answer</div>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="question-card hidden bg-white rounded-xl shadow-lg p-8 mb-8 border-4" style="border-color: #ECE852">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Maria has 4 different shirts and 3 different pants. How many different outfits can she make?</h2>
                    <input type="number" class="w-full p-4 border-2 rounded-lg focus:outline-none" style="border-color: #ECE852" placeholder="Enter your answer here">
                    <div class="error-message text-red-600 mt-2 hidden">Incorrect Answer. Try again.</div>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="question-card hidden bg-white rounded-xl shadow-lg p-8 mb-8 border-4" style="border-color: #ECE852">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Find the mean of these scores: 8, 5, 7, 9, 6</h2>
                    <input type="number" step="0.1" class="w-full p-4 border-2 rounded-lg focus:outline-none" style="border-color: #ECE852" placeholder="Enter the mean">
                    <div class="error-message text-red-600 mt-2 hidden">Incorrect calculation. Try again.</div>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="question-card hidden bg-white rounded-xl shadow-lg p-8 mb-8 border-4" style="border-color: #ECE852">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">What is the mean of these configuration times (in minutes): 23, 25, 28, 27, 30, 32?</h2>
                    <input type="number" step="0.1" class="w-full p-4 border-2 rounded-lg focus:outline-none" style="border-color: #ECE852" placeholder="Enter the mean">
                    <div class="error-message text-red-600 mt-2 hidden">Incorrect Answer. Try again.</div>
                </div>
            </div>

            <!-- Question 5 -->
            <div class="question-card hidden bg-white rounded-xl shadow-lg p-8 mb-8 border-4" style="border-color: #ECE852">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">How many ways can 4 books be arranged on a shelf?</h2>
                    <input type="number" class="w-full p-4 border-2 rounded-lg focus:outline-none" style="border-color: #ECE852" placeholder="Enter number of ways">
                    <div class="error-message text-red-600 mt-2 hidden">Incorrect Answer. Try again.</div>
                </div>
            </div>

            <!-- Question 6 -->
            <div class="question-card hidden bg-white rounded-xl shadow-lg p-8 mb-8 border-4" style="border-color: #ECE852">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">In how many ways can 3 students be chosen from a group of 5?</h2>
                    <input type="number" class="w-full p-4 border-2 rounded-lg focus:outline-none" style="border-color: #ECE852" placeholder="Enter number of ways">
                    <div class="error-message text-red-600 mt-2 hidden">Incorrect Answer. Try again.</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between mt-8">
            <button id="prevButton" class="px-8 py-3 rounded-lg bg-gray-100 hover:bg-gray-200" disabled>Previous</button>
            <button id="nextButton" class="px-8 py-3 rounded-lg text-white hover:opacity-90" style="background: #73EC8B">Next Question</button>
        </div>
    </div>

    <script>
        const correctAnswers = {
            1: 'combination_5C3=10',
            2: 12,
            3: 7,
            4: 27.5,
            5: 24,
            6: 10
        };

        document.addEventListener('DOMContentLoaded', function() {
            let quizProgress = {
    easy: { currentQuestion: 1, answers: Array(6).fill('') }
};
localStorage.setItem('quizProgress', JSON.stringify(quizProgress));

            let currentQuestion = quizProgress.easy.currentQuestion;
            const questionsArray = Array.from(document.querySelectorAll('.question-card'));

            function showQuestion(index) {
                questionsArray.forEach(q => q.classList.add('hidden'));
                questionsArray[index - 1].classList.remove('hidden');

                if(index === 1) {
                    const selectedValue = quizProgress.easy.answers[0];
                    if (selectedValue) {
                        const radio = questionsArray[0].querySelector(`input[value="${selectedValue}"]`);
                        if (radio) radio.checked = true;
                    }
                } else {
                    const input = questionsArray[index - 1].querySelector('input');
                    if (input) input.value = quizProgress.easy.answers[index - 1] || '';
                }
            }

            function validateAnswer() {
                const currentCard = questionsArray[currentQuestion - 1];
                const input = currentCard.querySelector('input');
                const error = currentCard.querySelector('.error-message');

                if (currentQuestion === 1) {
    const selected = document.querySelector('input[name="answer"]:checked');
    const errorMsg = document.querySelector('.question-card:nth-child(1) .error-message');
    if (!selected) {
        errorMsg.textContent = "Please select an answer";
        errorMsg.classList.remove('hidden');
        return false;
    }

    if (selected.value !== correctAnswers[1]) {
        errorMsg.textContent = "Incorrect answer. Try again.";
        errorMsg.classList.remove('hidden');
        return false;
    }

    errorMsg.classList.add('hidden');
    return true;
}


                if (!input.value.trim()) {
                    error.textContent = "Please enter an answer";
                    error.classList.remove('hidden');
                    input.style.borderColor = 'red';
                    return false;
                }

                const userAnswer = currentQuestion === 4 ? parseFloat(input.value) : parseInt(input.value);
                if (userAnswer !== correctAnswers[currentQuestion]) {
                    error.classList.remove('hidden');
                    input.style.borderColor = 'red';
                    return false;
                }

                error.classList.add('hidden');
                input.style.borderColor = '#ECE852';
                return true;
            }

            document.getElementById('nextButton').addEventListener('click', function() {
                if (!validateAnswer()) return;

                if (currentQuestion === 1) {
                    quizProgress.easy.answers[0] = questionsArray[0].querySelector('input[name="answer"]:checked').value;
                } else {
                    const input = questionsArray[currentQuestion - 1].querySelector('input');
                    quizProgress.easy.answers[currentQuestion - 1] = input.value;
                }

                if (currentQuestion < 6) {
                    currentQuestion++;
                    quizProgress.easy.currentQuestion = currentQuestion;
                    localStorage.setItem('quizProgress', JSON.stringify(quizProgress));
                    showQuestion(currentQuestion);
                    updateProgress();
                    document.getElementById('prevButton').disabled = false;
                } else {
                    alert('Quiz completed!');
                }
            });

            document.getElementById('prevButton').addEventListener('click', function() {
                currentQuestion--;
                quizProgress.easy.currentQuestion = currentQuestion;
                localStorage.setItem('quizProgress', JSON.stringify(quizProgress));
                showQuestion(currentQuestion);
                updateProgress();
                document.getElementById('prevButton').disabled = currentQuestion === 1;
            });

            function updateProgress() {
                document.getElementById('questionCounter').textContent = `${currentQuestion}/6`;
                document.getElementById('progressBar').style.width = `${(currentQuestion / 6) * 100}%`;
                document.getElementById('nextButton').textContent = currentQuestion === 6 ? 'Finish Quiz' : 'Next Question';
            }

            // Input validation listeners
            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('input', function() {
                    this.style.borderColor = '#ECE852';
                    this.parentElement.querySelector('.error-message').classList.add('hidden');
                });
            });

            // Radio button validation
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    questionsArray[0].querySelector('.error-message').classList.add('hidden');
                });
            });

            showQuestion(currentQuestion);
            updateProgress();
        });
    </script>
</body>
</html>
