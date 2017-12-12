<template>
<div class="column is-5">
  <div class="field is-horizontal">
      <div class="field-label is-normal">
          <label class="label has-text-left">Author</label>
      </div>
      <div class="field-body">
          <div class="field">
              <div class="control">
                  <input class="input" type="text" v-model="ipAuthor">
              </div>
          </div>
      </div>
  </div>
  <div class="field is-horizontal">
      <div class="field-label is-normal">
          <label class="label has-text-left">Activity</label>
      </div>
      <div class="field-body">
          <div class="field">
              <div class="control">
                  <input class="input" type="text" v-model="ipActivity">
              </div>
          </div>
      </div>
  </div>
</div>
</template>

<script>
export default {
    data: () => {
        return {
            ipAuthor: "",
            ipActivity: "",
        }
    },
}
</script>
