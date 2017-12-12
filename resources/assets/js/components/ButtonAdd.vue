<template>
  <div class="column">
    <button class="button vcenter" @click="extendLine()">
        <i v-bind:class="[show ? 'fa-minus' : 'fa-plus', 'fa']" aria-hidden="true"></i>
    </button>
  </article>
</div>
</template>

<script>
  export default {
    props: ['show'],
    methods: {
      extendLine: function (index) {
        this.$emit('extendLine');
      }
    }
  }
</script>
