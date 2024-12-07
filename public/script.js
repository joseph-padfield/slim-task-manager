const newTaskButton = document.querySelector("#new-task-button")
const newTaskForm = document.querySelector('.new-task-form')

newTaskButton.addEventListener('click', (e) => {
    newTaskForm.classList.toggle('hidden')
})

function closeEditContainer(taskId, originalTitle, originalDescription) {
    console.log("Task ID: " + taskId)
    console.log("Original Title: " + originalTitle)
    console.log("Original task description: " + originalDescription)
    document.getElementById('editDialog_' + taskId).close()
    document.getElementById('title_' + taskId).value = originalTitle
    document.getElementById('description_' + taskId).value = originalDescription
}

document.addEventListener('DOMContentLoaded', (event) => {
    const taskTitleFields = document.getElementsByClassName('task-table-title-field');

    for (let i = 0; i < taskTitleFields.length; i++) {
        taskTitleFields[i].addEventListener('click', (e) => {
            console.log(taskTitleFields[i].textContent);
        });
    }
});
