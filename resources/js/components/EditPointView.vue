<template>
      <point-form 
        title="Edit Point" 
        :loading="loading" 
        :error="error" 
        :point="point" 
        :originalPoint="originalPoint" 
        :onSubmit="savePoint" 
        :onReset="reset"
        :enableDelete="true"
      ></point-form>
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
        return true;
      }
      let nameIsTaken = false;
      this.otherPoints.forEach((other) => {
        if (this.point.name === other.name) {
          nameIsTaken = true;
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
        !Number.isInteger(Number(this.point.x)) ||
        !Number.isInteger(Number(this.point.y)) ||
        this.point.name === "" ||
        nameIsTaken
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
