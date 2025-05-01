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
                <span class="px-4 py-2 rounded-full bg-gray-100">Average Level</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Question</span>
                    <span class="font-bold" style="color: #ECE852" id="questionCounter">1/5</span>
                </div>
                <div class="ml-4 text-sm text-gray-700 font-semibold">
                    Remaining Clue: <span id="clueCount">2</span>
                </div>
            </div>
        </div>

       <!-- Progress Bar -->
       <div class="h-3 bg-gray-200 rounded-full mb-8">
        <div class="h-full rounded-full transition-all duration-300" id="progressBar" style="width: 16.66%; background: #73EC8B"></div>
    </div>

      <!-- Questions Container -->
<div id="questionsContainer">

</div>

       <!-- Navigation -->
       <div class="flex justify-between mt-8">
        <button id="prevButton" class="px-8 py-3 rounded-lg bg-gray-100 hover:bg-gray-200" disabled>Previous</button>
        <button id="nextButton" class="px-8 py-3 rounded-lg text-white hover:opacity-90" style="background: #73EC8B">Next Question</button>
    </div>
</div>

<script>
    const clues = {
        1: "You can pick the same kind more than once. How many ways to pick 3 from 5 types?",
        2: "Pick 3 digits from 0–9, but no repeats. Think: choices for 1st, 2nd, and 3rd digit.",
        3: "Put the numbers in order. What’s in the middle?",
        4: "How many ways to mix 5 letters if one repeats?",
        5: "Order matters — one person per job!",
    };

    const correctAnswers = {
        1: 'combination_7C3=35',
        2: 720,
        3: 17.5,
        4: 60,
        5: 210
    };

    const questionData = [
        {
            type: 'mcq',
            question: 'A bakery offers five different types of cupcakes. You want to buy three cupcakes. How many different ways can you select three cupcakes if you can buy multiple cupcakes of the same type? Is this a permutation or combination problem?',
            options: [
                { text: 'Combination problem, 9C3 = 10', value: 'combination_9C3=10' },
                { text: 'Permutation problem, 1P3 = 60', value: 'permutation_1P3=60' },
                { text: 'Combination problem, 7C3 = 35', value: 'combination_7C3=35' }, //correct answer
                { text: 'Permutation problem, 5P2 = 20', value: 'permutation_5P2=20' }
            ]
        },
        {
            type: 'input',
            question: 'A school locker requires a 3-digit combination where digits cannot repeat, and digits range from 0 to 9. How many different combinations are possible?'
        },
        {
            type: 'input',
            question: 'A small company has 10 employees. Their monthly salaries (in thousands of pesos) are: 15, 18, 16, 15, 20, 25, 15, 18, 22, 17. Calculate the median of the employees\' monthly salaries.'
        },
        {
            type: 'input',
            question: 'From the word "APPLE", how many different arrangements can be made?'
        },
        {
            type: 'input',
            question: 'In how many ways can a president, a vice-president, and a secretary be chosen from 7 people, assuming one person can only hold one position?'
        },

    ];

    let clueUsed = false;
    let currentQuestion = 1;
    const totalQuestions = 5;
    const questionsContainer = document.getElementById('questionsContainer');

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
                <div class="error-message text-red-600 mt-2 hidden">Incorrect Answer. Try again.</div>
            </div>
        `;
        card.innerHTML = questionHTML;
        questionsContainer.appendChild(card);
    });

    function showQuestion(index) {
        document.querySelectorAll('.question-card').forEach((card, i) => {
            card.classList.toggle('hidden', i !== index - 1);
        });
        document.getElementById('questionCounter').textContent = `${index}/5`;
        document.getElementById('progressBar').style.width = `${(index / totalQuestions) * 100}%`;
        document.getElementById('prevButton').disabled = index === 1;
        document.getElementById('nextButton').textContent = index === totalQuestions ? 'Proceed' : 'Next Question';
    }

    function validateAnswer() {
        const card = document.querySelectorAll('.question-card')[currentQuestion - 1];
        const error = card.querySelector('.error-message');
        const clueBtn = card.querySelector('.clue-btn');

        if (questionData[currentQuestion - 1].type === 'mcq') {
            const selected = card.querySelector('input[type="radio"]:checked');
            if (!selected) {
                error.textContent = "Please select an answer";
                error.classList.remove('hidden');
                return false;
            }
            if (selected.value !== correctAnswers[currentQuestion]) {
                error.textContent = "Incorrect answer. Try again.";
                error.classList.remove('hidden');
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
                error.textContent = "Incorrect answer. Try again.";
                error.classList.remove('hidden');
                input.style.borderColor = 'red';
                return false;
            }
            input.style.borderColor = '#ECE852';
        }

        error.classList.add('hidden');
        return true;
    }

    document.getElementById('nextButton').addEventListener('click', () => {
        if (!validateAnswer()) return;
        if (currentQuestion < totalQuestions) {
            currentQuestion++;
            showQuestion(currentQuestion);
        } else {
            // All questions completed — redirect to average level
            window.location.href = "/difficult";
        }
        });

    document.getElementById('prevButton').addEventListener('click', () => {
        if (currentQuestion > 1) {
            currentQuestion--;
            showQuestion(currentQuestion);
        }
    });

    let remainingClues = 2;

document.querySelectorAll('.clue-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if (remainingClues <= 0) return;

        const qNum = parseInt(btn.dataset.question);
        const clueText = btn.nextElementSibling;

        // Only show clue if not already shown
        if (clueText.classList.contains('hidden')) {
            clueText.textContent = clues[qNum];
            clueText.classList.remove('hidden');
            remainingClues--;
            document.getElementById('clueCount').textContent = remainingClues;
        }

        // Disable all clue buttons if no clues left
        if (remainingClues === 0) {
            document.querySelectorAll('.clue-btn').forEach(b => b.disabled = true);
        }
    });
});


    showQuestion(currentQuestion);
</script>
</body>
</html>
