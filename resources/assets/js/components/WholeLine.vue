
<template>
    <div class="columns">
        <left-line :time="time"></left-line>
        <right-line v-if="show"></right-line>
        <btn-add @extendLine="extendLine" :show="show"></btn-add>
    </div>

</template>

<script>
  import LeftLine from './LeftLine.vue'
  import RightLine from './RightLine.vue'
  import ButtonAdd from './ButtonAdd.vue'
  export default {
    props: ['time', 'show', 'index'],
    components: {
      'left-line': LeftLine,
      'right-line': RightLine,
      'btn-add': ButtonAdd,
    },
    methods: {
      extendLine: function() {
        this.$emit('extendLine', this.index);
      }
    }
  }
</script>
