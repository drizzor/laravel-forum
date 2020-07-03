<template>
  <li class="dropdown nav-item" v-if="notifications.length">
    <button class="btn btn-link nav-link" id="dropdownMenuButton" data-toggle="dropdown">
      <i class="fas fa-bell"></i>
    </button>

    <ul class="dropdown-menu">
      <li v-for="notification in notifications" :key="notification.id">
        <a
          class="dropdown-item"
          :href="notification.data.link"
          v-text="notification.data.message"
          @click="markAsRead(notification)"
        ></a>
      </li>
    </ul>
  </li>
</template>

<script>
export default {
  data() {
    return {
      notifications: false
    };
  },

  created() {
    axios
      .get("/profiles/" + window.App.user.name + "/notifications")
      .then(response => (this.notifications = response.data));
  },

  methods: {
    markAsRead(notification) {
      axios.delete(
        "/profiles/" +
          window.App.user.name +
          "/notifications/" +
          notification.id
      );
    }
  }
};
</script>