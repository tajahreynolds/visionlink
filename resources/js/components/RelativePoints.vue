<template>
  <div>
    <div v-if="calculating || loading">
        Calculating relative points...
    </div>
    <div v-else>
        <h2>Nearest Points at distance {{ relative.minDistance }}</h2>
        <p v-for="near in relative.nearestPoints">{{ near }}</p>
        <h2>Farthest Points at distance {{ relative.maxDistance }}</h2>
        <p v-for="far in relative.farthestPoints">{{ far }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    point: Object,
  },
  data() {
    return {
      otherPoints: [],
      loading: true,
      error: null,
    };
  },
  computed: {
    calculating: function () {
        return (this.point.x === "" || isNaN(Number(this.point.x)) || this.point.x === null)
            || (this.point.y === "" || isNaN(Number(this.point.y)) || this.point.y === null);
    },
    relative: function () {
      let minDistance = Number.MAX_SAFE_INTEGER;
      let maxDistance = Number.MIN_SAFE_INTEGER;
      let nearestPoints = [];
      let farthestPoints = [];

      this.otherPoints.forEach((other) => {
        let distance = this.distanceBetween(this.point, other);
        
        if (distance < minDistance) {
          minDistance = distance;
          nearestPoints = [other]; // reset the array with the new nearest point
        } else if (distance === minDistance) {
          nearestPoints.push(other);
        }

        if (distance > maxDistance) {
          maxDistance = distance;
          farthestPoints = [other];
        } else if (distance === maxDistance) {
          farthestPoints.push(other);
        }
      });

      minDistance = Math.round(minDistance * 10) / 10;
      maxDistance = Math.round(maxDistance * 10) / 10;

      return {
        nearestPoints,
        minDistance,
        farthestPoints,
        maxDistance,
      };
    },
  },
  methods: {
    async fetchOtherPoints() {
      this.loading = true;
      this.error = null;
      axios
        .get(`http://localhost:8000/api/points`)
        .then((res) => {
          this.otherPoints = res.data.filter(
            (other) => other.id != this.point.id
          );
        })
        .catch((err) => {
          console.error(err);
          this.error = "Failed to fetch other points";
        })
        .finally(() => {
          this.loading = false;
        });
    },
    distanceBetween(point, other) {
      return Math.sqrt(
        Math.pow(other.x - point.x, 2) + Math.pow(other.y - point.y, 2)
      );
    },
  },
  mounted() {
    this.fetchOtherPoints();
  },
};
</script>
