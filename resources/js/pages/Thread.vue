<script>
import Replies from "../components/Replies.vue";
import SubscribeButton from "../components/SubscribeButton.vue";

export default {
  props: ["thread"],

  components: { Replies, SubscribeButton },

  data() {
    return {
      repliesCount: this.thread.replies_count,
      locked: this.thread.locked,
      editing: false
    };
  },

  methods: {
    lock() {
      this.locked = true;
      axios.put("/locked-threads/" + this.thread.slug);
    },

    unlock() {
      this.locked = false;
      axios.put("/unlocked-threads/" + this.thread.slug);
    }
  }
};
</script>