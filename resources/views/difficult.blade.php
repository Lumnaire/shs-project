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
                <span class="px-4 py-2 rounded-full bg-gray-100">Difficult Level</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Question</span>
                    <span class="font-bold" style="color: #ECE852" id="questionCounter">1/3</span>
                </div>
                <div class="ml-4 text-sm text-gray-700 font-semibold">
                    Remaining Clue: <span id="clueCount">3</span>
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
        1: "You pick 3 letters and 2 numbers. Order matters, and repeats are OK.",
        2: "Count choices for each letter and number — they can repeat.",
        3: `• Mean Clue: Multiply hours by number of students, add it up, then divide by 20.
            • Median Clue: Find the middle student in the list.
            • Mode Clue: Which number of hours has the most students?`


    };

    const correctAnswers = {
        1: { type: 'permutation', value: 1757600 },
        2: 676000,
        3: { mean: 3.95, median: 4, mode: 4 }

    };

    const questionData = [
        {
            type: 'hybrid',
        question: 'A password must contain three letters and two numbers. How many different passwords are possible if repetition is allowed and the letters must be lowercase and the numbers 0-9? Is this a permutation or combination problem? ',
        options: [
            { text: 'Combination problem', value: 'combination' },
            { text: 'Permutation problem', value: 'permutation' }
        ]
        },
        {
            type: 'input',
            question: 'A license plate consists of 2 letters followed by 3 digits (e.g., AB-123). If letters and digits can be repeated, how many unique license plates are possible? '
        },
        {
    type: 'frequency-analysis',
    question: `The following frequency distribution table shows the number of hours 20 students spent studying for a major exam:<br><br>
    <table class="table-auto w-full text-left border mt-4 mb-6">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Hours Studied</th>
                <th class="border px-4 py-2">Number of Students</th>
            </tr>
        </thead>
        <tbody>
            <tr><td class="border px-4 py-2">2</td><td class="border px-4 py-2">3</td></tr>
            <tr><td class="border px-4 py-2">3</td><td class="border px-4 py-2">5</td></tr>
            <tr><td class="border px-4 py-2">4</td><td class="border px-4 py-2">6</td></tr>
            <tr><td class="border px-4 py-2">5</td><td class="border px-4 py-2">4</td></tr>
            <tr><td class="border px-4 py-2">6</td><td class="border px-4 py-2">2</td></tr>
        </tbody>
    </table>
    Calculate the approximate <strong>mean</strong>, <strong>median class</strong>, and <strong>mode class</strong> of the hours studied.`
}

    ];

    let clueUsed = false;
    let currentQuestion = 1;
    const totalQuestions = 3;
    const questionsContainer = document.getElementById('questionsContainer');

    // Generate question cards
    questionData.forEach((q, index) => {
        const card = document.createElement('div');
        card.className = `question-card bg-white rounded-xl shadow-lg p-8 mb-8 border-4 ${index > 0 ? 'hidden' : ''}`;
        card.style.borderColor = '#ECE852';

        const questionHTML = `
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-4">${q.question}</h2>
        ${q.type === 'mcq' || q.type === 'hybrid' ? `
            <div class="space-y-4 mb-6" id="answerOptions">
                ${q.options.map((opt, i) => `
                    <div class="answer-option flex items-center gap-3 p-4 rounded-lg border hover:border-green-400 cursor-pointer">
                        <input type="radio" name="answer${index + 1}" class="h-5 w-5" id="q${index + 1}_opt${i}" value="${opt.value}">
                        <label for="q${index + 1}_opt${i}" class="flex-1">${opt.text}</label>
                    </div>
                `).join('')}
            </div>
        ` : ''}
        ${q.type === 'input' || q.type === 'hybrid' ? `
            <input type="number" step="any" class="w-full p-4 border-2 rounded-lg focus:outline-none" style="border-color: #ECE852" placeholder="Enter your answer here">
        ` : ''}
        <button class="mt-4 px-4 py-2 bg-yellow-100 text-yellow-800 rounded clue-btn" data-question="${index + 1}">Show Clue</button>
        <div class="clue-text mt-2 text-blue-600 font-medium hidden"></div>
        <div class="error-message text-red-600 mt-2 hidden">Incorrect Answer. Try again.</div>
        ${q.type === 'frequency-analysis' ? `
    <div class="grid gap-4">
        <input type="number" step="any" class="mean-input p-3 border-2 rounded-lg" style="border-color: #ECE852" placeholder="Mean (e.g., 10.95)">
        <input type="number" class="median-input p-3 border-2 rounded-lg" style="border-color: #ECE852" placeholder="Median class (e.g., 8.3)">
        <input type="number" class="mode-input p-3 border-2 rounded-lg" style="border-color: #ECE852" placeholder="Mode class (e.g., 3.1)">
    </div>
` : ''}

    </div>
`;

        card.innerHTML = questionHTML;
        questionsContainer.appendChild(card);
    });

    function showQuestion(index) {
        document.querySelectorAll('.question-card').forEach((card, i) => {
            card.classList.toggle('hidden', i !== index - 1);
        });
        document.getElementById('questionCounter').textContent = `${index}/3`;
        document.getElementById('progressBar').style.width = `${(index / totalQuestions) * 100}%`;
        document.getElementById('prevButton').disabled = index === 1;
        document.getElementById('nextButton').textContent = index === totalQuestions ? 'Finish Quiz' : 'Next Question';
    }

    function validateAnswer() {
    const card = document.querySelectorAll('.question-card')[currentQuestion - 1];
    const error = card.querySelector('.error-message');

    const qType = questionData[currentQuestion - 1].type;

    if (qType === 'mcq') {
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

    } else if (qType === 'input') {
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

    } else if (qType === 'hybrid') {
        const selected = card.querySelector('input[type="radio"]:checked');
        const input = card.querySelector('input[type="number"]');

        if (!selected || !input.value.trim()) {
            error.textContent = "Please answer both parts";
            error.classList.remove('hidden');
            return false;
        }

        const radioCorrect = selected.value === correctAnswers[currentQuestion].type;
        const inputCorrect = parseFloat(input.value) === correctAnswers[currentQuestion].value;

        if (!radioCorrect || !inputCorrect) {
            error.textContent = "Incorrect answer. Try again.";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        input.style.borderColor = '#ECE852';
    } else if (qType === 'frequency-analysis') {
    const meanInput = card.querySelector('.mean-input');
    const medianInput = card.querySelector('.median-input');
    const modeInput = card.querySelector('.mode-input');

    if (!meanInput.value || !medianInput.value || !modeInput.value) {
        error.textContent = "Please fill in all three fields";
        error.classList.remove('hidden');
        return false;
    }

    const userMean = parseFloat(meanInput.value);
    const userMedian = parseInt(medianInput.value);
    const userMode = parseInt(modeInput.value);

    const { mean, median, mode } = correctAnswers[currentQuestion];

    const meanCorrect = Math.abs(userMean - mean) < 0.05; // allow margin
    const medianCorrect = userMedian === median;
    const modeCorrect = userMode === mode;

    if (!meanCorrect || !medianCorrect || !modeCorrect) {
        error.textContent = "Incorrect answer. Try again.";
        error.classList.remove('hidden');
        return false;
    }

    error.classList.add('hidden');
    return true;
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
            alert('Proceed assembling the wire!');
              window.location.href = "/";
        }
    });

    document.getElementById('prevButton').addEventListener('click', () => {
        if (currentQuestion > 1) {
            currentQuestion--;
            showQuestion(currentQuestion);
        }
    });

    let remainingClues = 3;

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
