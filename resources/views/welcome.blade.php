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
            <h1 class="text-4xl font-bold mb-4" style="color: #73EC8B">TangledDigits</h1>
            <div class="flex items-center justify-center gap-4">
                <span class="px-4 py-2 rounded-full bg-gray-100">Easy Level</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Question</span>
                    <span class="font-bold" style="color: #ECE852" id="questionCounter">1/6</span>
                </div>
                <div class="ml-4 text-sm text-gray-700 font-semibold">
                    Remaining Clue: <span id="clueCount">1</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="h-3 bg-gray-200 rounded-full mb-8">
            <div class="h-full rounded-full transition-all duration-300" id="progressBar" style="width: 16.66%; background: #73EC8B"></div>
        </div>

        <!-- Questions Container -->
        <div id="questionsContainer"></div>

        <!-- Navigation -->
        <div class="flex justify-between mt-8">
            <button id="prevButton" class="px-8 py-3 rounded-lg bg-gray-100 hover:bg-gray-200" disabled>Previous</button>
            <button id="nextButton" class="px-8 py-3 rounded-lg text-white hover:opacity-90" style="background: #73EC8B">Next Question</button>
        </div>
    </div>

    <!-- Flashscreen 1: Auto Advance After 3 Seconds -->
    <div id="flash1" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
        <img src="/game-cover.png" class="w-full h-full object-contain">
    </div>

    <!-- Flashscreen 2: Click to Continue -->
    <div id="flash2" class="fixed inset-0 z-40 hidden flex items-center justify-center bg-white cursor-pointer">
        <img src="/game-about.png" class="w-full h-full object-contain">
    </div>

    <!-- Flashscreen 3: Click to Continue -->
    <div id="flash3" class="fixed inset-0 z-30 hidden flex items-center justify-center bg-white cursor-pointer">
        <img src="/game-instructions.png" class="w-full h-full object-contain">
    </div>

    <script>
        const clues = {
            1: "You’re picking 3 students from 5. Does the order you pick them matter?",
            2: "Multiply how many shirts by how many pants. That gives all the outfit choices.",
            3: "Add all the scores and divide by how many there are.",
            4: "Add all the times together and divide by 6.",
            5: "How many ways can you line up 4 books?",
            6: "You’re just choosing, not putting in order."
        };

        const correctAnswers = {
            1: 'combination_5C3=10',
            2: 12,
            3: 7,
            4: 27.5,
            5: 24,
            6: 10
        };

        const questionData = [
            {
                type: 'mcq',
                question: 'A school needs to select three students from a group of five to form a cleanup committee. The order they are chosen doesn\'t matter. How many different cleanup committees are possible? Is this a permutation or combination problem?',
                options: [
                    { text: 'Combination problem, 5C3 = 10', value: 'combination_5C3=10' },
                    { text: 'Permutation problem, 5P3 = 60', value: 'permutation_5P3=60' },
                    { text: 'Combination problem, 5C2 = 10', value: 'combination_5C2=10' },
                    { text: 'Permutation problem, 5P2 = 20', value: 'permutation_5P2=20' }
                ]
            },
            { type: 'input', question: 'Maria has 4 different shirts and 3 different pants. How many different outfits can she make?' },
            { type: 'input', question: 'Find the mean of these scores: 8, 5, 7, 9, 6' },
            { type: 'input', question: 'What is the mean of these configuration times (in minutes): 23, 25, 28, 27, 30, 32?' },
            { type: 'input', question: 'How many ways can 4 books be arranged on a shelf?' },
            { type: 'input', question: 'In how many ways can 3 students be chosen from a group of 5?' }
        ];

        let clueUsed = false;
        let currentQuestion = 1;
        const totalQuestions = 6;
        const questionsContainer = document.getElementById('questionsContainer');

        const errorMessages = [
            "Mali nganiii",
            "Ata baya maliii",
            "Ay mali?"
        ];

        function showErrorMessage(container) {
            const randomError = errorMessages[Math.floor(Math.random() * errorMessages.length)];
            const randomImageIndex = Math.floor(Math.random() * 7) + 1;
            const errorImage = `<img src="/incorrect-${randomImageIndex}.jpg" alt="Incorrect" class="mt-2 w-48 h-auto">`;
            container.innerHTML = `${randomError}<br>${errorImage}`;
            container.classList.remove('hidden');
        }

        // Generate question cards
        questionData.forEach((q, index) => {
            const card = document.createElement('div');
            card.className = `question-card bg-white rounded-xl shadow-lg p-8 mb-8 border-4 ${index > 0 ? 'hidden' : ''}`;
            card.style.borderColor = '#ECE852';

            const questionHTML = `
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">${q.question}</h2>
                    ${q.type === 'mcq' ? `
                        <div class="space-y-4" id="answerOptions">
                            ${q.options.map((opt, i) => `
                                <div class="answer-option flex items-center gap-3 p-4 rounded-lg border hover:border-green-400 cursor-pointer">
                                    <input type="radio" name="answer${index + 1}" class="h-5 w-5" id="q${index + 1}_opt${i}" value="${opt.value}">
                                    <label for="q${index + 1}_opt${i}" class="flex-1">${opt.text}</label>
                                </div>
                            `).join('')}
                        </div>
                    ` : `
                        <input type="number" step="any" class="w-full p-4 border-2 rounded-lg focus:outline-none" style="border-color: #ECE852" placeholder="Enter your answer here">
                    `}
                    <button class="mt-4 px-4 py-2 bg-yellow-100 text-yellow-800 rounded clue-btn" data-question="${index + 1}">Show Clue</button>
                    <div class="clue-text mt-2 text-blue-600 font-medium hidden"></div>
                    <div class="error-message text-red-600 mt-2 hidden"></div>
                </div>
            `;
            card.innerHTML = questionHTML;
            questionsContainer.appendChild(card);
        });

        function showQuestion(index) {
            document.querySelectorAll('.question-card').forEach((card, i) => {
                card.classList.toggle('hidden', i !== index - 1);
            });
            document.getElementById('questionCounter').textContent = `${index}/6`;
            document.getElementById('progressBar').style.width = `${(index / totalQuestions) * 100}%`;
            document.getElementById('prevButton').disabled = index === 1;
            document.getElementById('nextButton').textContent = index === totalQuestions ? 'Finish Quiz' : 'Check Answer';
        }

        function validateAnswer() {
            const card = document.querySelectorAll('.question-card')[currentQuestion - 1];
            const error = card.querySelector('.error-message');

            if (questionData[currentQuestion - 1].type === 'mcq') {
                const selected = card.querySelector('input[type="radio"]:checked');
                if (!selected || selected.value !== correctAnswers[currentQuestion]) {
                    showErrorMessage(error);
                    return false;
                }
            } else {
                const input = card.querySelector('input[type="number"]');
                if (!input.value.trim()) {
                    error.textContent = "Please enter an answer";
                    error.classList.remove('hidden');
                    input.style.borderColor = 'red';
                    return false;
                }
                const userAnswer = parseFloat(input.value);
                if (userAnswer !== correctAnswers[currentQuestion]) {
                    showErrorMessage(error);
                    input.style.borderColor = 'red';
                    return false;
                }
                input.style.borderColor = '#ECE852';
            }

            error.classList.add('hidden');
            return true;
        }

        function showImageForQuestion(index) {
    const card = document.querySelectorAll('.question-card')[index - 1];
    if (card.querySelector('.circuit-image')) return;

    // Show circuit image
    const circuitImage = document.createElement('img');
    circuitImage.src = `/color-${index}.png`;
    circuitImage.alt = `Circuit Color ${index}`;
    circuitImage.className = 'mt-4 circuit-image';

    const circuitLabel = document.createElement('p');
    circuitLabel.className = 'mt-4 font-semibold text-green-600';
    circuitLabel.textContent = `Here is your circuit color:`;

    const clueBtn = card.querySelector('.clue-btn');
    clueBtn.insertAdjacentElement('afterend', circuitLabel);
    circuitLabel.insertAdjacentElement('afterend', circuitImage);

    // Random correct image
    const correctImageIndex = Math.floor(Math.random() * 8) + 1;
    const correctImage = document.createElement('img');
    correctImage.src = `/correct-${correctImageIndex}.jpg`;
    correctImage.alt = 'Correct';
    correctImage.className = 'mt-4 w-48 h-auto';

    // Random positive message
    const correctMessages = [
        "Naks naman",
        "Sana ol",
        "Kuya how to be u po",
        "Ka astig man uy"
    ];
    const randomMessage = correctMessages[Math.floor(Math.random() * correctMessages.length)];

    const messageLabel = document.createElement('p');
    messageLabel.className = 'mt-2 font-medium text-green-500';
    messageLabel.textContent = randomMessage;

    circuitImage.insertAdjacentElement('afterend', messageLabel);
    messageLabel.insertAdjacentElement('afterend', correctImage);
}


        let answerChecked = false;

        document.getElementById('nextButton').addEventListener('click', () => {
            if (!answerChecked) {
                if (!validateAnswer()) return;
                showImageForQuestion(currentQuestion);
                answerChecked = true;
                document.getElementById('nextButton').textContent =
                    currentQuestion === totalQuestions ? 'Proceed to Next Stage' : 'Proceed to Next Question';
            } else {
                if (currentQuestion < totalQuestions) {
                    currentQuestion++;
                    showQuestion(currentQuestion);
                    document.getElementById('nextButton').textContent = 'Check Answer';
                    answerChecked = false;
                } else {
                    window.location.href = "/average";
                }
            }
        });

        document.getElementById('prevButton').addEventListener('click', () => {
            if (currentQuestion > 1) {
                currentQuestion--;
                showQuestion(currentQuestion);
                answerChecked = false;
                document.getElementById('nextButton').textContent = 'Check Answer';
            }
        });

        // Clue buttons logic
        document.querySelectorAll('.clue-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (clueUsed) return;

                const qNum = parseInt(btn.dataset.question);
                const clueText = btn.nextElementSibling;
                clueText.textContent = clues[qNum];
                clueText.classList.remove('hidden');

                clueUsed = true;
                document.getElementById('clueCount').textContent = '0';
                document.querySelectorAll('.clue-btn').forEach(b => b.disabled = true);
            });
        });

        // Splash screen logic
        window.addEventListener('DOMContentLoaded', () => {
            const flash1 = document.getElementById('flash1');
            const flash2 = document.getElementById('flash2');
            const flash3 = document.getElementById('flash3');

            setTimeout(() => {
                flash1.classList.add('hidden');
                flash2.classList.remove('hidden');
            }, 3000);

            flash2.addEventListener('click', () => {
                flash2.classList.add('hidden');
                flash3.classList.remove('hidden');
            });

            flash3.addEventListener('click', () => {
                flash3.classList.add('hidden');
                document.querySelector('body').classList.remove('overflow-hidden');
            });

            document.querySelector('body').classList.add('overflow-hidden');
        });

        showQuestion(currentQuestion);
    </script>
</body>
</html>
