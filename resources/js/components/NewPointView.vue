<template>
  <div class="container">
    <div>
      <h1>New Point</h1>
      <form @submit.prevent="createPoint">
        <div style="display: flex; flex-direction: column">
          <input v-model="point.name" placeholder="Enter a name for the new point"/>
          <input v-model="point.x" placeholder="new point x value"/>
          <input v-model="point.y" placeholder="new point y value"/>
        </div>
        <button type="submit">Save</button>
        <button v-on:click="cancel" type="button">Cancel</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      point: { name: null, x: null, y: null },
      loading: true,
      error: null,
    };
  },
  methods: {
    async creat2ePoint() {
      this.loading = true;
      this.error = null;
      axios
        .post(`http://localhost:8000/api/points`, this.point)
        .catch((err) => {
          console.error(err);
          this.error = "Failed to create point.";
        })
        .finally(() => {
          this.loading = false;
          !this.error && this.$router.push("/");
        });
    },
    cancel() {
      this.$router.push("/");
    },
  },
};
</script>
