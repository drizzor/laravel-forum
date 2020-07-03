<template>
  <input type="file" accept="image/*" @change="onChange" title="Selectionner un avatar" />
</template>

<script>
export default {
  methods: {
    // Dès que l'utilisateur va sélectionner une image, on doit modifier l'affichage de celle-ci sur l'interface
    onChange(e) {
      if (!e.target.files.length) return;

      // Le fichier envoyé (uniquement le premier selec)
      let file = e.target.files[0];

      // Lecture du fichier et affichage dès que possible (une fois chargé)
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = e => {
        // Pas hésiter à faire un log sur e afin de voir que c'est bien de result que nous avons besoin
        // console.log(e);
        this.$emit("loaded", {
          src: e.target.result,
          file
        });
      };
    }
  }
};
</script>