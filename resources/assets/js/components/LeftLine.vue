<template>
<div class="column is-5">
    <div class="field is-horizontal">
      <div class="field-label is-normal">
        <label class="label has-text-left">Start</label>
      </div>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <input class="input" type="text" v-model="ipStart">
          </div>
        </div>
      </div>
    </div>
    <div class="field is-horizontal">
      <div class="field-label is-normal">
        <label class="label has-text-left">End</label>
      </div>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <input class="input" type="text" v-model="ipEnd">
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
  export default {
    props: ['time'],
    template: '#leftLine',
    data: () => {
        return {
            ipStart: "",
            ipEnd: "",
        }
    },
    mounted: function () {
        this.ipStart = this.time;
        this.ipEnd = this.time;
    },
  }
</script>
