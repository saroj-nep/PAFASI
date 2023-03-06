<template>
  <div>
    <!-- This section displays a message to the user regarding when they will receive the results from the specialties referrals made-->
    <div>
      <h2 style="font-size: 1.2em" class="h1 text-white text-left">
        Sie haben eine Überweisung an das Fachgebiet/die Fachgebiete
        ausgestellt. Das Ergebnis wird Ihnen in ein paar Wochen (nach Abschluss
        der heutigen Untersuchung) zugeschickt. Fahren Sie mit der Untersuchung
        der Patientin/des Patienten fort.​
      </h2>
      <!-- This button redirects the user to the main menu page -->
      <div class="flex justify-center">
        <button
          style=""
          type="button"
          class="button btn shadow-[0_9px_0_rgb(0,0,0)] hover:shadow-[0_4px_0px_rgb(0,0,0)] text-black bg-white ease-out hover:translate-y-1 transition-all rounded shadow-2xl"
          @click.prevent="goToEvents()"
        >
          Zum Hauptmenü gehen
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Popup from "@/components/Popup.vue";
import { ref } from "vue";
import Notepad from "@/components/Notepad.vue";

export default {
  methods: {
    // This function reloads the current page
    goToEvents: function () {
      document.location.reload();
    },
  },
  data() {
    return {
      // These variables are used to  hide Tooltip and Notepad on load of the page
      showTooltip: false,
      showNotepad: false,
    };
  },
  setup() {
    // This function initializes variables that control the popup component
    const popupTriggers = ref({
      buttonTrigger: false,
    });
    const TogglePopup = (trigger) => {
      console.log(trigger, "trigger");
      popupTriggers.value[trigger] = !popupTriggers.value[trigger];
    };
    return {
      // These components are used in the template section
      Popup,
      popupTriggers,
      TogglePopup,
    };
  },

  // These components are used in the template section
  components: { Notepad, Popup },
};
</script>


<style scoped>
h2 {
  background: rgb(5, 150, 105);
  padding: 20px;
  border-radius: 10px;
  margin: 10px auto;

  /* width: 50%; */

  color: rgb(255, 255, 255);
}

.button {
  background: #be123c;
  color: white;
  padding: 2%;
  border-radius: 20px;
  margin-right: 5%;
  margin-left: 10%;
  margin-top: 1%;
  width: 30%;
  /* width: 20%; */
  cursor: pointer;
  /* color: #444   */
}
</style>
