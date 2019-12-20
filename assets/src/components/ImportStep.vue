<template>
  <select v-model="step.action">
    <option></option>
    <option value="newPost">create a post</option>
    <option value="newUser">create a user</option>
    <option value="addMeta" v-if="stepIndex !== 0">add meta</option>
  </select>
</template>

<script>
export default {};
</script>

<style></style>
