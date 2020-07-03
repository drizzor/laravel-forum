<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li class="page-item" v-show="prevUrl">
            <a
                class="page-link"
                href="#"
                aria-label="Previous"
                rel="prev"
                @click.prevent="page--"
            >
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <!-- <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li> -->
        <li class="page-item" v-show="nextUrl">
            <a
                class="page-link"
                href="#"
                aria-label="Next"
                rel="next"
                @click.prevent="page++"
            >
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    props: ["dataSet"],

    data() {
        return {
            page: 1,
            prevUrl: false,
            nextUrl: false
        };
    },

    watch: {
        dataSet() {
            this.page = this.dataSet.current_page;
            this.prevUrl = this.dataSet.prev_page_url;
            this.nextUrl = this.dataSet.next_page_url;
        },

        // Regarde au moindre changement de page afin de pouvoir déterminer si on doit changer les informations à afficher (changement de page)
        page() {
            this.broadcast().updateUrl();
        }
    },

    computed: {
        shouldPaginate() {
            return !!this.prevUrl || !!this.nextUrl;
        }
    },

    methods: {
        // Lance l'event updated
        broadcast() {
            return this.$emit("changed-page", this.page);
        },

        updateUrl() {
            history.pushState(null, null, "?page=" + this.page);
        }
    }
};
</script>
