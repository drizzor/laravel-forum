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
      editing: false,
      form: {
        title: this.thread.title,
        body: this.thread.body
      },
      error: []
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
    },

    update() {
      axios
        .put("/threads/" + this.thread.channel.slug + "/" + this.thread.slug, {
          title: this.form.title,
          body: this.form.body
        })
        .then(() => {
          flash("Le sujet a bien été modifié.");
        })
        .catch(error => {
          flash(
            "Erreur: la modification n'a pas pu être effectuée !",
            "danger"
          );

          if (
            error.response.data.errors.title ||
            error.response.data.errors.body
          ) {
            this.error = error.response.data.errors.body;
            this.cancel();
          }
        });

      this.editing = false;
    },

    // Si on annule la modification, remettre l'info précédente
    cancel() {
      this.form.title = this.thread.title;
      this.form.body = this.thread.body;
      this.editing = false;
    }
  }
};
</script>
