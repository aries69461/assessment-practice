document.addEventListener('DOMContentLoaded', () => {
    const addQuestForm = document.getElementById('add-quest-form');
    const questListContainer = document.getElementById('quest-list');
    const adminMessage = document.getElementById('admin-message');

    // Function to display messages in the admin panel
    function showAdminMessage(message, type) {
        adminMessage.textContent = message;
        adminMessage.className = `message ${type}`;
        adminMessage.style.display = 'block';
        setTimeout(() => {
            adminMessage.style.display = 'none';
        }, 3000); // Hide after 3 seconds
    }

    // Function to fetch and display existing quests for deletion
    async function fetchAndDisplayQuests() {
        try {
            const response = await fetch('api.php?action=getQuests'); // Fetch quests from backend
            const quests = await response.json();

            questListContainer.innerHTML = '<h3>Existing Quests</h3>'; // Clear and re-add title
            if (quests.length > 0) {
                quests.forEach(quest => {
                    const questItem = document.createElement('div');
                    questItem.classList.add('quest-item');
                    questItem.innerHTML = `
                        <span>${quest.Quest_Name} (ID: ${quest.Quest_ID}) - Points: ${quest.Point_Value}</span>
                        <button class="delete-quest-btn" data-quest-id="${quest.Quest_ID}">Delete</button>
                    `;
                    questListContainer.appendChild(questItem);
                });
            } else {
                questListContainer.innerHTML += '<p>No quests found.</p>';
            }
        } catch (error) {
            // console.error('Error fetching quests:', error);
            showAdminMessage('Failed to load quests. ' + error.message, 'error');
        }
    }

    // Handle adding a new quest
    addQuestForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const questName = document.getElementById('quest-name').value;
        const questDescription = document.getElementById('quest-description').value;
        const questAnswer = document.getElementById('quest-answer').value;
        const questPoints = document.getElementById('quest-points').value;
        const questDifficulty = document.getElementById('quest-difficulty').value;

        try {
            const response = await fetch('api.php?action=addQuest', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: questName,
                    description: questDescription,
                    answer: questAnswer,
                    points: questPoints,
                    difficulty: questDifficulty
                }),
            });

            const result = await response.json();
            if (result.success) {
                showAdminMessage('Quest added successfully!', 'success');
                addQuestForm.reset(); // Clear form
                fetchAndDisplayQuests(); // Refresh list
            } else {
                showAdminMessage('Error adding quest: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Error adding quest:', error);
            showAdminMessage('Failed to add quest: ' + error.message, 'error');
        }
    });

    // Handle deleting a quest
    questListContainer.addEventListener('click', async (event) => {
        if (event.target.classList.contains('delete-quest-btn')) {
            const questId = event.target.dataset.questId;
            if (confirm(`Are you sure you want to delete Quest ID: ${questId}?`)) {
                try {
                    const response = await fetch('api.php?action=deleteQuest', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ quest_id: questId }),
                    });

                    const result = await response.json();
                    if (result.success) {
                        showAdminMessage('Quest deleted successfully!', 'success');
                        fetchAndDisplayQuests(); // Refresh list
                    } else {
                        showAdminMessage('Error deleting quest: ' + result.message, 'error');
                    }
                } catch (error) {
                    console.error('Error deleting quest:', error);
                    showAdminMessage('Failed to delete quest: ' + error.message, 'error');
                }
            }
        }
    });

    // Initial load of quests when admin page loads
    fetchAndDisplayQuests();
});