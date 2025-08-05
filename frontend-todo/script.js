let tasks = [];
let taskIdCounter = 0;

// Toggle between light and dark mode
function toggleTheme() {
    const body = document.body;
    const theme = body.getAttribute('data-theme');
    const newTheme = theme === 'light' ? '' : 'light';

    body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme || 'dark');
}

// Load saved theme when page opens
function loadTheme() {
    if (localStorage.getItem('theme') === 'light') {
        document.body.setAttribute('data-theme', 'light');
    }
}

// Add a new task
function addTask(e) {
    e.preventDefault();

    const input = document.getElementById('taskInput');
    const text = input.value.trim();

    if (text) {
        tasks.push({ id: taskIdCounter++, text, completed: false });
        input.value = '';
        renderTasks();
        updateStats();
    }
}

// Mark a task as done / not done
function toggleTask(id) {
    const task = tasks.find(t => t.id === id);
    if (task) {
        task.completed = !task.completed;
        renderTasks();
        updateStats();
    }
}

// Remove a task
function deleteTask(id) {
    tasks = tasks.filter(t => t.id !== id);
    renderTasks();
    updateStats();
}

// Edit task text
function editTask(id) {
    const task = tasks.find(t => t.id === id);
    const item = [...document.querySelectorAll('.task-item')]
        .find(el => el.querySelector('.edit-btn')?.onclick?.toString().includes(`${id}`));

    if (task && item) {
        const span = item.querySelector('.task-text');
        const original = task.text;

        const input = document.createElement('input');
        input.type = 'text';
        input.value = original;
        input.className = 'edit-input';
        input.style.cssText = 'border:1px solid #ccc;border-radius:5px;padding:4px;font-size:1em;width:70%';

        item.replaceChild(input, span);
        input.focus();

        input.addEventListener('blur', save);
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') save();
            if (e.key === 'Escape') cancel();
        });

        function save() {
            const newText = input.value.trim();
            if (newText) {
                task.text = newText;
                renderTasks();
                updateStats();
            } else {
                cancel();
            }
        }

        function cancel() {
            renderTasks();
        }
    }
}

// Show all tasks
function renderTasks() {
    const list = document.getElementById('taskList');
    list.innerHTML = '';

    tasks.forEach(task => {
        const li = document.createElement('li');
        li.className = `task-item ${task.completed ? 'completed' : ''}`;
        li.innerHTML = `
            <div class="task-checkbox" onclick="toggleTask(${task.id})"></div>
            <span class="task-text">${task.text}</span>
            <button class="edit-btn" onclick="editTask(${task.id})">✏️</button>
            <button class="delete-btn" onclick="deleteTask(${task.id})">🗑️</button>
        `;
        list.appendChild(li);
    });
}

// Show how many tasks are done
function updateStats() {
    const done = tasks.filter(t => t.completed).length;
    const total = tasks.length;
    const percent = total ? (done / total) * 100 : 0;

    document.getElementById('numbers').textContent = `${done} / ${total}`;
    document.getElementById('progress').style.width = `${percent}%`;
}

// Start everything when the page loads
function initializeApp() {
    loadTheme();

    document.getElementById('taskForm').addEventListener('submit', addTask);
    updateStats();

    // Demo tasks
    setTimeout(() => {
        if (tasks.length === 0) {
            sampleTasks.forEach(text => {
                tasks.push({ id: taskIdCounter++, text, completed: false });
            });
            tasks[0].completed = true;
            renderTasks();
            updateStats();
        }
    }, 1000);
}

// / Run when page loads
document.addEventListener('DOMContentLoaded', initializeApp);
