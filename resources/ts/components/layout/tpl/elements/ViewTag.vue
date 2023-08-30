<template>
<span class="h-tag" :class="className">{{title}}</span>
</template>

<script>
export default {
  name: "ViewTag",
  props: {
    title: String,
    className: String,
  }
}
</script>
