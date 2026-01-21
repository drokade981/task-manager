<template>
  <div>
    <h2>Task Manager</h2>
    
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
          <td>
            <!-- Status dropdown -->
            <select v-model="task.status" @change="updateStatus(task)">
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
            </select>
          </td>
          <td>{{ task.due_date }}</td>
          <td>
            <button>Edit</button> &nbsp;
            <button>Delete</button>
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
      }
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
    }

  }
};
</script>