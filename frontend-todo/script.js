// pushing the orders in an array
let tasks = [];
let taskIdCounter = 0;

function toggleTheme() {
    const body = document.body;
    const currentTheme = body.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? null : 'light'; // Toggle between light and dark
    
   
    body.setAttribute('data-theme', newTheme || '');
    
    
    localStorage.setItem('theme', newTheme || 'dark');
}


function loadTheme() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        document.body.setAttribute('data-theme', 'light');
    }
}

// TASK MANAGEMENT FUNCTIONS

// Function to add a new task
function addTask(event) {
    event.preventDefault(); // Prevent form from submitting normally
    
    const input = document.getElementById('taskInput');
    const taskText = input.value.trim(); // Remove extra spaces
    
    
    if (taskText) {
        // Create a new task object
        const task = {
            id: taskIdCounter++, // Give each task a unique ID
            text: taskText,
            completed: false
        };
        
        
        tasks.push(task);
        
        // Clear the input field
        input.value = '';
        
        // Update the display
        renderTasks();
        updateStats();
    }
}

// Function to toggle a task between completed and not completed
function toggleTask(taskId) {
    // Find the task with the matching ID
    const task = tasks.find(t => t.id === taskId);
    if (task) {
        // Toggle the completed status
        task.completed = !task.completed;
        
        // Update the display
        renderTasks();
        updateStats();
    }
}

// Function to delete a task
function deleteTask(taskId) {
    // Remove the task from the tasks array
    tasks = tasks.filter(t => t.id !== taskId);
    
    // Update the display
    renderTasks();
    updateStats();
}
// to edit
function editTask(taskId) {
    const task = tasks.find(t => t.id === taskId);
    if (task) {
        const newText = prompt('Edit task:', task.text);
        if (newText !== null) {
            task.text = newText.trim();
            renderTasks();
            updateStats();
        }
    }
}



// DISPLAY FUNCTIONS

// Function to display all tasks on the page
function renderTasks() {
    const taskList = document.getElementById('taskList');
    taskList.innerHTML = ''; // Clear existing tasks
    
    // Create HTML for each task
    tasks.forEach(task => {
        const li = document.createElement('li');
        li.className = `task-item ${task.completed ? 'completed' : ''}`;
        
        // Create the HTML content for this task
        li.innerHTML = `
            <div class="task-checkbox" onclick="toggleTask(${task.id})"></div>
            <span class="task-text">${task.text}</span>
            <button class="edit-btn" onclick="editTask(${task.id})">✏️</button>
            <button class="delete-btn" onclick="deleteTask(${task.id})">🗑️</button>
        `;
        
        // Add the task to the list
        taskList.appendChild(li);
    });
}

// Function to update the progress stats (numbers and progress bar)
function updateStats() {
    const completedTasks = tasks.filter(task => task.completed).length;
    const totalTasks = tasks.length;
    
    // Calculate percentage (avoid division by zero)
    const percentage = totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0;
    
    // Update the numbers display
    document.getElementById('numbers').textContent = `${completedTasks} / ${totalTasks}`;
    
    // Update the progress bar width
    document.getElementById('progress').style.width = `${percentage}%`;
}

// INITIALIZATION

// Function that runs when the page loads
function initializeApp() {
    // Load the saved theme
    loadTheme();
    
    // Set up form submission
    const form = document.getElementById('taskForm');
    form.addEventListener('submit', addTask);
    
    // Initialize stats display
    updateStats();
    
    // Add some sample tasks for demonstration (after a short delay)
    setTimeout(() => {
        if (tasks.length === 0) {
            
            
            // Add each sample task
            sampleTasks.forEach(text => {
                tasks.push({
                    id: taskIdCounter++,
                    text: text,
                    completed: false
                });
            });
            
            // Mark the first task as completed for demonstration
            tasks[0].completed = true;
            
            // Update the display
            renderTasks();
            updateStats();
        }
    }, 1000); // Wait 1 second before adding sample tasks
}

// Start the app when the page loads
document.addEventListener('DOMContentLoaded', initializeApp);
