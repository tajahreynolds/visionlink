<template>
  <div class="container">
    <div
      v-if="loading"
      class="d-flex justify-content-center align-content-center"
    >
      <div class="spinner-grow mt-5"></div>
    </div>
    <div v-else>
      <h1 class="text-center mt-5">Edit Point</h1>
      <div v-if="error">
        <p class="text-danger">{{ error.message }}</p>
        <p
          v-for="err in error.errors"
          class="text-danger"
        >
          {{ err[0] }}
        </p>
      </div>
      <form
        v-if="point"
        @submit.prevent="savePoint"
      >
        <div style="display: flex; flex-direction: column">
          <label
            for="pointName"
            class="form-label"
            >Name</label
          >
          <input
            v-model="point.name"
            id="pointName"
            class="form-control"
          />
          <br />
          <label
            for="pointX"
            class="form-label"
            >X Value</label
          >
          <input
            v-model="point.x"
            id="pointX"
            class="form-control"
          />
          <br />
          <label
            for="pointY"
            class="form-label"
            >Y Value</label
          >
          <input
            v-model="point.y"
            id="pointY"
            class="form-control"
          />
        </div>
        <div style="display: flexbox; margin-top: 8px">
          <button
            type="submit"
            class="btn btn-primary"
            :disabled="!formIsUpdated || dataInvalid"
          >
            Save
          </button>
          <button
            type="button"
            v-on:click="deletePoint"
            class="btn btn-danger"
          >
            Delete
          </button>
          <button
            type="button"
            v-on:click="cancel"
            style="float: right; margin-left: 4px"
            class="btn btn-dark"
          >
            Cancel
          </button>
          <button
            type="button"
            v-on:click="reset"
            :disabled="!formIsUpdated"
            style="float: right"
            class="btn btn-success"
          >
            Reset
          </button>
        </div>
      </form>
      <relative-points :point="point" v-on:loaded-other-points="otherPoints = $event"></relative-points>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: {
    id: [String, Number],
  },
  data() {
    return {
      point: null,
      originalPoint: null,
      otherPoints: null,
      loading: true,
      error: null,
    };
  },
  computed: {
    dataInvalid: function () {
      if (!this.otherPoints) {
        return false;
      }
      let nameTaken = false;
      this.otherPoints.forEach(other => {
        if (this.point.name === other.name) {
          nameTaken = true;
        }
      });
      return (
        this.point === null ||
        this.point.x === null ||
        this.point.y === null ||
        this.point.x === "" ||
        this.point.y === "" ||
        isNaN(Number(this.point.x)) ||
        isNaN(Number(this.point.y)) ||
        this.point.name === "" ||
        nameTaken
      );
    },
    formIsUpdated: function () {
      return (
        this.point.x !== this.originalPoint.x ||
        this.point.y !== this.originalPoint.y ||
        this.point.name !== this.originalPoint.name
      );
    },
  },
  methods: {
    async fetchPointData() {
      this.loading = true;
      this.error = null;
      axios
        .get(`http://localhost:8000/api/points/${this.id}`)
        .then((res) => {
          this.point = res.data;
          this.originalPoint = { ...res.data };
        })
        .catch((err) => {
          this.error = err.response.data;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    async savePoint() {
      this.loading = true;
      this.error = null;
      axios
        .put(`http://localhost:8000/api/points/${this.id}`, this.point)
        .catch((err) => {
          this.error = err.response.data;
        })
        .finally(() => {
          this.loading = false;
          if (this.error === null) {
            this.$router.push("/");
          }
        });
    },
    async deletePoint() {
      if (!confirm("Are you sure you want to delete this point?")) {
        return;
      }
      this.loading = true;
      this.error = null;
      axios
        .delete(`http://localhost:8000/api/points/${this.id}`)
        .catch((err) => {
          this.error = err.response.data;
        })
        .finally(() => {
          this.loading = false;
          if (this.error === null) {
            this.$router.push("/");
          }
        });
    },
    cancel() {
      this.$router.push("/");
    },
    reset() {
      this.error = null;
      this.point = { ...this.originalPoint };
    },
  },
  mounted() {
    this.fetchPointData();
  },
};
</script>
