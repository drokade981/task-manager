<template>
  <div>
    <h2>Task Manager</h2>

    <form @submit.prevent="submitTask">
      <input v-model="newTask.title" placeholder="Title" required />  &nbsp;
      <input v-model="newTask.description" placeholder="Description" />  &nbsp;
      <select v-model="newTask.status">
        <option value="pending">Pending</option>
        <option value="in_progress">In Progress</option>
        <option value="completed">Completed</option>
      </select>  &nbsp;
      <input type="date" v-model="newTask.due_date" />  &nbsp;
      <button type="submit">{{ editingTaskId ? 'Update Task' : 'Add Task' }}</button> &nbsp;
      <button type="button" @click="resetForm" v-if="editingTaskId">Cancel</button>
    </form>

    <br/>
    <hr/>
    
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Status</th>
          <th>Due Date</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(task, index) in tasks" :key="task.id">
          <td>{{ (pagination.current_page - 1) * 10 + index + 1 }}</td>
          <td>{{ task.title }}</td>
          <td width="50%">{{ task.description }}</td>
          <td>{{task.status}}</td>
          <td>{{ task.due_date }}</td>
          <td>
            <button @click="editTask(task)">Edit</button> &nbsp;
            <button @click="deleteTask(task.id)">Delete</button>
          </td>
        </tr>
      </tbody>
      
    </table>
    <div style="margin-top:20px;">
  <button 
    :disabled="pagination.current_page === 1" 
    @click="loadTasks(pagination.current_page - 1)">
    Previous
  </button>

  Page {{ pagination.current_page }} of {{ pagination.last_page }}

  <button 
    :disabled="pagination.current_page === pagination.last_page" 
    @click="loadTasks(pagination.current_page + 1)">
    Next
  </button>
</div>

  </div>
</template>

<style scoped>
table th {
  text-align: left;
}
</style>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      tasks: [],
        pagination: {
        current_page: 1,
        last_page: 1
      },
      newTask: { title: '', description: '', status: 'pending', due_date: '' },
      editingTaskId: null
    };
  },
  mounted() {
    this.loadTasks();
  },
  methods: {
    
    async loadTasks(page = 1) {
      const response = await axios.get(`/api/tasks?page=${page}`);
      this.tasks = response.data.data;
      this.pagination.current_page = response.data.meta.current_page;
      this.pagination.last_page = response.data.meta.last_page;
    },

    async editTask(task) {
      this.newTask = { ...task };
      this.editingTaskId = task.id;
    },

    async submitTask() {
      if (this.editingTaskId) {
        await axios.put(`/api/tasks/${this.editingTaskId}`, this.newTask);
      } else {
        await axios.post('/api/tasks', this.newTask);
      }
      this.resetForm();
      this.loadTasks();
    },

    resetForm() {
      this.newTask = { title: '', description: '', status: 'pending', due_date: '' };
      this.editingTaskId = null;
    },

    async deleteTask(id) {
      await axios.delete(`/api/tasks/${id}`);
      this.loadTasks();
    },
  }
};
</script>