<template>
  <teleport to="body">
    <div
      ref="modal"
      class="modal fade"
      :class="{ show: active, 'd-block': active }"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header text-dark">
            <h5 class="modal-title">{{ title }}</h5>
          </div>
          <div class="modal-body text-dark">
            <p>{{ body }}</p>
          </div>
          <div class="modal-footer text-dark">
            <button type="button" class="btn btn-secondary" @click="$emit('closeModal')">Close</button>
            <button type="button" class="btn btn-primary" @click="$emit('validModal')">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </teleport>
</template>

<script>
export default {
  name: "BootstrapModal",
  emits: ["closeModal, validModal"],
  props: {
    showModal: Boolean,
    id: Number,
    title: String,
    body: String,
  },
  watch: {
    showModal: {
      handler(newVal) {
        this.active = newVal;
        const body = document.querySelector("body");
        this.showModal ? body.classList.add("modal-open") : body.classList.remove("modal-open")
      },
      immediate: true,
      deep: true,
    },
  },
  data() {
    return {
      active: this.showModal,
    };
  },
};
</script>

<style lang="scss" scoped></style>