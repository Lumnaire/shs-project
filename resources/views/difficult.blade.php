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
                <span class="px-4 py-2 rounded-full bg-gray-100">Difficult Level</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Question</span>
                    <span class="font-bold" style="color: #ECE852" id="questionCounter">1/5</span>
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

<!-- Completion Modal -->
<div id="completionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md w-full text-center">
      <h2 class="text-2xl font-bold mb-4 text-green-600">🎉 Congratulations!</h2>
      <p class="mb-4 text-gray-700">Proceed assembling wires!</p>
      <img src="/quiz-done.jpg" alt="Quiz Completed" class="mx-auto mb-6 w-48 h-48 object-cover">
      <button id="confirmBtn" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
        Confirm
      </button>
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
    3: 3.95, // mean
    4: 4,    // median
    5: 4     // mode
};

const frequencyTable = `
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
</table>`;


const questionData = [
    {
        type: 'hybrid',
        question: 'A password must contain three letters and two numbers. How many different passwords are possible if repetition is allowed and the letters must be lowercase and the numbers 0-9? Is this a permutation or combination problem?',
        options: [
            { text: 'Combination problem', value: 'combination' },
            { text: 'Permutation problem', value: 'permutation' }
        ]
    },
    {
        type: 'input',
        question: 'A license plate consists of 2 letters followed by 3 digits (e.g., AB-123). If letters and digits can be repeated, how many unique license plates are possible?'
    },
    {
        type: 'input',
        question: `Using the frequency table below, calculate the <strong>Approximate Mean</strong> of the hours studied: ${frequencyTable}`
    },
    {
        type: 'input',
        question: `Using the frequency table below, what is the <strong>Median Class</strong> of the hours studied? ${frequencyTable}`
    },
    {
        type: 'input',
        question: `Using the frequency table below, what is the <strong>Mode Class</strong> of the hours studied? ${frequencyTable}`
    }
];


    let clueUsed = false;
    let currentQuestion = 1;
    const totalQuestions = 5;
    const correctMessages = [
    "Naks naman!",
    "Sana ol!",
    "Kuya how to be u po?",
    "Ka astig man uy!"
];

const wrongMessages = [
    "Try again, beshie!",
    "Oops, not quite!",
    "Halaka, mali!",
    "Balik sa calculator or bumalik kana sakin!",
    "Uy, di tama yan!",
    "Ngek! Mali!",
    "Wrong nanaman 😢",
    "Almost but not quite!"
];

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
        document.getElementById('questionCounter').textContent = `${index}/${totalQuestions}`;
        document.getElementById('progressBar').style.width = `${(index / totalQuestions) * 100}%`;
        document.getElementById('prevButton').disabled = index === 1;
        document.getElementById('nextButton').textContent = index === totalQuestions ? 'Finish Quiz' : 'Check Answer';
    }

    function validateAnswer() {
    const card = document.querySelectorAll('.question-card')[currentQuestion - 1];
    const error = card.querySelector('.error-message');
    error.classList.add('hidden');

    const qType = questionData[currentQuestion - 1].type;

    // Question 1: hybrid (radio + input)
    if (currentQuestion === 1) {
        const selected = card.querySelector('input[type="radio"]:checked');
        const input = card.querySelector('input[type="number"]');
        if (!selected || !input.value.trim()) {
            error.textContent = "Please answer both parts";
            error.classList.remove('hidden');
            return false;
        }

        const radioCorrect = selected.value === correctAnswers[1].type;
        const inputCorrect = parseInt(input.value) === correctAnswers[1].value;

        if (!radioCorrect || !inputCorrect) {
            error.textContent = "Incorrect answer. Try again.";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        input.style.borderColor = '#ECE852';
        return true;
    }

    // Question 2: standard input
    if (currentQuestion === 2) {
        const input = card.querySelector('input[type="number"]');
        if (!input.value.trim()) {
            error.textContent = "Please enter an answer";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        const userAnswer = parseInt(input.value);
        if (userAnswer !== correctAnswers[2]) {
            error.textContent = "Incorrect answer. Try again.";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        input.style.borderColor = '#ECE852';
        return true;
    }

    // Question 3: mean (allow small error)
    if (currentQuestion === 3) {
        const input = card.querySelector('input[type="number"]');
        if (!input.value.trim()) {
            error.textContent = "Please enter an answer";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        const userAnswer = parseFloat(input.value);
        const correct = correctAnswers[3];
        const isCorrect = Math.abs(userAnswer - correct) < 0.05;

        if (!isCorrect) {
            error.textContent = "Incorrect answer. Try again.";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        input.style.borderColor = '#ECE852';
        return true;
    }

    // Question 4: median
    if (currentQuestion === 4) {
        const input = card.querySelector('input[type="number"]');
        if (!input.value.trim()) {
            error.textContent = "Please enter an answer";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        const userAnswer = parseInt(input.value);
        if (userAnswer !== correctAnswers[4]) {
            error.textContent = "Incorrect answer. Try again.";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        input.style.borderColor = '#ECE852';
        return true;
    }

    // Question 5: mode
    if (currentQuestion === 5) {
        const input = card.querySelector('input[type="number"]');
        if (!input.value.trim()) {
            error.textContent = "Please enter an answer";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        const userAnswer = parseInt(input.value);
        if (userAnswer !== correctAnswers[5]) {
            error.textContent = "Incorrect answer. Try again.";
            error.classList.remove('hidden');
            input.style.borderColor = 'red';
            return false;
        }

        input.style.borderColor = '#ECE852';
        return true;
    }

    return true;
}




let state = 'check'; // or 'next'

document.getElementById('nextButton').addEventListener('click', () => {
    const card = document.querySelectorAll('.question-card')[currentQuestion - 1];

    if (state === 'check') {
        const valid = validateAnswer();

        const existingFeedback = card.querySelector('.feedback-message');
        if (existingFeedback) existingFeedback.remove();

        const existingSuccess = card.querySelector('.success-msg');
        if (existingSuccess) existingSuccess.remove();

        if (!valid) {
            const wrongMsg = wrongMessages[Math.floor(Math.random() * wrongMessages.length)];
            const imgNum = Math.floor(Math.random() * 8) + 1;

            const feedback = document.createElement('div');
            feedback.className = 'feedback-message text-center mt-6';

            const img = document.createElement('img');
            img.src = `/incorrect-${imgNum}.jpg`;
            img.className = "w-32 h-32 mx-auto";

            const msg = document.createElement('p');
            msg.className = 'text-red-600 font-semibold mt-2';
            msg.textContent = wrongMsg;

            feedback.appendChild(img);
            feedback.appendChild(msg);
            card.appendChild(feedback);
            return;
        }

        // Show correct feedback
        const feedback = document.createElement('div');
        feedback.className = 'success-msg text-center mt-6';
        const correctImgNum = Math.floor(Math.random() * 7) + 1;
        const correctMsg = correctMessages[Math.floor(Math.random() * correctMessages.length)];

        if (currentQuestion === 1) {
            const title = document.createElement('p');
            title.textContent = "Here is your color:";
            title.className = "font-semibold text-lg mb-2";

            const colorImg = document.createElement('img');
            colorImg.src = "/color-4.png";
            colorImg.alt = "Color Preview";
            colorImg.className = "mt-4 w-full max-w-xs mx-auto rounded shadow-md";

            const correctImg = document.createElement('img');
            correctImg.src = `/correct-${correctImgNum}.jpg`;
            correctImg.className = "mt-2 w-full max-w-xs mx-auto ";

            const msg = document.createElement('p');
            msg.textContent = correctMsg;
            msg.className = "text-green-600 font-bold text-xl mt-2";

            feedback.appendChild(title);
            feedback.appendChild(colorImg);
            feedback.appendChild(correctImg);
            feedback.appendChild(msg);
        } else if (currentQuestion === 2) {
            const title = document.createElement('p');
            title.textContent = "Here is your circuit color:";
            title.className = "font-semibold text-lg mb-2";

            const colorImg = document.createElement('img');
            colorImg.src = "/color-5.png";
            colorImg.alt = "Circuit Color Preview";
            colorImg.className = "mx-auto w-40 h-40 mb-4";

            const correctImg = document.createElement('img');
            correctImg.src = `/correct-${correctImgNum}.jpg`;
            correctImg.className = "mx-auto w-32 h-32";

            const msg = document.createElement('p');
            msg.textContent = correctMsg;
            msg.className = "text-green-600 font-bold text-xl mt-2";

            feedback.appendChild(title);
            feedback.appendChild(colorImg);
            feedback.appendChild(correctImg);
            feedback.appendChild(msg);
        } else {
    const imgMap = {
        3: 'color-2.png', // mean
        4: 'color-7.png', // median
        5: 'color-8.png'  // mode
    };

    const title = document.createElement('p');
    title.textContent = "Here is your circuit color:";
    title.className = "font-semibold text-lg mb-2";

    const colorImg = document.createElement('img');
    colorImg.src = `/${imgMap[currentQuestion]}`;
    colorImg.alt = "Color Preview";
    colorImg.className = "mx-auto w-40 h-40 mb-4";

    const correctImg = document.createElement('img');
    correctImg.src = `/correct-${correctImgNum}.jpg`;
    correctImg.className = "mx-auto w-32 h-32";

    const msg = document.createElement('p');
    msg.textContent = correctMsg;
    msg.className = "text-green-600 font-bold text-xl mt-2";

    feedback.appendChild(title);
    feedback.appendChild(colorImg);
    feedback.appendChild(correctImg);
    feedback.appendChild(msg);
}


        card.appendChild(feedback);

        if (currentQuestion < totalQuestions) {
            document.getElementById('nextButton').textContent = 'Next Question';
            state = 'next';
        } else {
            document.getElementById('nextButton').textContent = 'Finish Quiz';
            state = 'next';
        }

    } else if (state === 'next') {
        if (currentQuestion < totalQuestions) {
    currentQuestion++;
    showQuestion(currentQuestion);
    document.getElementById('nextButton').textContent = 'Check Answer';
    state = 'check';
} else {
    document.getElementById('completionModal').classList.remove('hidden');

}

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

    function showModal() {
    document.getElementById('quizModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('quizModal').classList.add('hidden');
}

document.getElementById('confirmBtn').addEventListener('click', () => {
    window.location.href = "/";
});


</script>
</body>
</html>
