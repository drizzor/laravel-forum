// Le fichier mixin on peut le voir un peut comme un Trait avec laravel
// Ceci nous permet de rentre plus propre certain fichiers
export default {
    data() {
        return {
            items: []
        };
    },
    methods: {
        add(item) {
            this.items.push(item);

            this.$emit("added");
        },
        remove(index) {
            this.items.splice(index, 1);

            this.$emit("removed");
        }
    }
};
