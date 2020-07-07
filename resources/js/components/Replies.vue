<template>
  <div>
    <!-- la clé va permettre à VUEJS de récupérer le bon élément lors de la supression  -->
    <div v-for="(reply, index) in items" :key="reply.id">
      <reply :reply="reply" @deleted="remove(index)"></reply>
    </div>

    <!-- Si l'event @updated est lancé, executer la méthode fetch -->
    <paginator :dataSet="dataSet" @changed-page="fetch"></paginator>
    <h5 v-if="$parent.locked">Ce thread a été verouillé. Il n'est plus possible d'y répondre.</h5>
    <new-reply @created="add" v-else></new-reply>
  </div>
</template>

<script>
import Reply from "./Reply.vue";
import NewReply from "./NewReply.vue";
import collection from "../mixins/collection";

export default {
  components: { Reply, NewReply },

  mixins: [collection],

  data() {
    return {
      dataSet: false
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch(page) {
      axios.get(this.url(page)).then(this.refresh);
    },

    url(page) {
      if (!page) {
        let query = location.search.match(/page=(\d+)/);
        page = query ? query[1] : 1;
      }
      return location.pathname + "/replies?page=" + page;
    },

    refresh({ data }) {
      this.dataSet = data;
      this.items = data.data;
      // Lorsque la nouvelle page est chargée on force a être en dessus de page (et non en fin)
      window.scrollTo(0, 0);
    }
  }
};
</script>
