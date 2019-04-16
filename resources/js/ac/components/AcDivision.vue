<template>
    
</template>

<script>
    export default {
        name: "AcDivision",
        props: {
          id: {
              type: Number,
              required: true
          },
          name: {
              type: String,
              required: true
          },
          organization_id: {
              type: Number,
              required: true
          },
          type: {
              type: Number,
              default: 1
          }
        },
        data: function () {
            return {
                persons: function () {
                    
                }
            }
        }
    }
</script>

<style scoped>

</style>
