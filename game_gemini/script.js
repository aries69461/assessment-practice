document.addEventListener('DOMContentLoaded', () => {
    const questsContainer = document.getElementById('quests-container');
    const questPopup = document.getElementById('quest-popup');
    const completionPopup = document.getElementById('completion-popup');
    const closePopupBtn = document.getElementById('close-popup');
    const submitAnswerBtn = document.getElementById('submit-answer');
    const returnToQuestsBtn = document.getElementById('return-to-quests');
    const feedbackMessage = document.getElementById('feedback-message');

    let currentQuest = null; // To store the quest being attempted

    // Function to fetch and display quests [cite: 23]
    async function fetchQuests() {
        // In a real application, this would be an API call to your backend (e.g., PHP)
        // For this example, we'll use mock data.
        const mockQuests = [
            { id: 1, name: 'The Ancient Riddle', description: 'What has an eye, but cannot see?', value: 50, difficulty: 'Easy', answer: 'needle' },
            { id: 2, name: 'Math Challenge', description: 'What is 15 * 7?', value: 100, difficulty: 'Medium', answer: '105' },
            { id: 3, name: 'Logic Puzzle', description: 'I am always hungry, I must always be fed, The finger I lick will soon turn red. What am I?', value: 150, difficulty: 'Hard', answer: 'fire' },
            { id: 4, name: 'History Quiz', description: 'Who was the first president of the United States?', value: 75, difficulty: 'Easy', answer: 'george washington' }
        ];

        questsContainer.innerHTML = ''; // Clear existing quests
        mockQuests.forEach(quest => {
            const questCard = document.createElement('div');
            questCard.classList.add('quest-card');
            questCard.innerHTML = `
                <h3>${quest.name}</h3>
                <p>${quest.description}</p>
                <p><strong>Points:</strong> ${quest.value}</p>
                <button class="start-quest-btn" data-quest-id="${quest.id}">Start Quest</button>
            `;
            questsContainer.appendChild(questCard);
        });
    }

    // Event listener for "Start Quest" buttons [cite: 32]
    questsContainer.addEventListener('click', (event) => {
        if (event.target.classList.contains('start-quest-btn')) {
            const questId = parseInt(event.target.dataset.questId);
            // In a real app, you'd fetch quest details from the server based on questId
            // For now, find from mock data
            const mockQuests = [
                { id: 1, name: 'The Ancient Riddle', description: 'What has an eye, but cannot see?', value: 50, difficulty: 'Easy', answer: 'needle' },
                { id: 2, name: 'Math Challenge', description: 'What is 15 * 7?', value: 100, difficulty: 'Medium', answer: '105' },
                { id: 3, name: 'Logic Puzzle', description: 'I am always hungry, I must always be fed, The finger I lick will soon turn red. What am I?', value: 150, difficulty: 'Hard', answer: 'fire' },
                { id: 4, name: 'History Quiz', description: 'Who was the first president of the United States?', value: 75, difficulty: 'Easy', answer: 'george washington' }
            ];
            currentQuest = mockQuests.find(q => q.id === questId);

            if (currentQuest) {
                document.getElementById('popup-quest-name').textContent = currentQuest.name;
                document.getElementById('popup-quest-description').textContent = currentQuest.description;
                document.getElementById('popup-quest-points').textContent = currentQuest.value;
                document.getElementById('player-answer').value = ''; // Clear previous answer
                feedbackMessage.textContent = ''; // Clear previous feedback
                questPopup.style.display = 'flex';// Show the popup [cite: 35]
            }
        }
    });

    // Event listener for submitting an answer [cite: 36]
    submitAnswerBtn.addEventListener('click', () => {
        const playerAnswer = document.getElementById('player-answer').value.trim().toLowerCase();
        if (currentQuest) {
            if (playerAnswer === currentQuest.answer.toLowerCase()) {
                feedbackMessage.textContent = 'Correct! Points awarded.';
                feedbackMessage.style.color = 'green';
                setTimeout(() => {
                    questPopup.style.display = 'none'; // Hide quest popup
                    showCompletionScreen(currentQuest.value); // Show completion screen [cite: 41]
                    // In a real app: Send data to backend to update player points [cite: 44]
                }, 1000);
            } else {
                feedbackMessage.textContent = 'Incorrect. Please try again.'; // [cite: 38]
                feedbackMessage.style.color = 'red';
            }
        }
    });

    // Event listener for closing the quest popup
    closePopupBtn.addEventListener('click', () => {
        questPopup.style.display = 'none';
    });

    // Function to show the completion screen [cite: 40]
    function showCompletionScreen(pointsEarned) {
        document.getElementById('earned-points').textContent = pointsEarned; // [cite: 41]
        document.getElementById('completion-time').textContent = new Date().toLocaleString(); // [cite: 42]
        completionPopup.style.display = 'flex';
    }

    // Event listener for "Return to Quests" button [cite: 43]
    returnToQuestsBtn.addEventListener('click', () => {
        completionPopup.style.display = 'none';
        fetchQuests(); // Reload quests (useful if new quests become available or for session management)
    });

    // Initial load of quests
    fetchQuests();
});