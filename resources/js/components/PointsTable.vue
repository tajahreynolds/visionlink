<template>
  <table>
    <thead>
      <tr>
        <th scope="col">Name</th>
        <th
          class="text-center"
          scope="col"
        >
          X
        </th>
        <th
          class="text-center"
          scope="col"
        >
          Y
        </th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="point in points"
        :key="point.id"
      >
        <router-link
          v-if="editable"
          :to="{ name: 'EditPoint', params: { id: point.id } }"
        >
          <th scope="row">{{ point.name }}</th>
          <td class="text-center">{{ point.x }}</td>
          <td class="text-center">{{ point.y }}</td>
        </router-link>
        <div v-else style="display: contents;">
          <th scope="row">{{ point.name }}</th>
          <td class="text-center">{{ point.x }}</td>
          <td class="text-center">{{ point.y }}</td>
        </div>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: {
    points: Array,
    editable: Boolean,
  },
  mounted() {
    this.points.sort((a, b) => a.name.localeCompare(b.name));
  }
};
</script>

<style scoped>
a {
  display: contents;
}

table {
  margin: auto;
  width: 80%;
  border-spacing: collapse;
  border: 2px solid rgb(140 140 140);
  font-family: sans-serif;
  font-size: 0.8rem;
  letter-spacing: 1px;
}

th,
td {
  border: 1px solid rgb(160 160 160);
  padding: 8px 10px;
}

tbody > tr:hover {
  background-color: lightgray;
}
</style>
