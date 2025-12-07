import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Import jQuery and expose globally
import $ from "jquery";
window.$ = window.jQuery = $;

// Import Bootstrap JS
import "bootstrap/dist/js/bootstrap.bundle.min.js";

// Expose Bootstrap globally
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;

// Import Select2
import "select2/dist/js/select2.min.js";

// Import SortableJS
import Sortable from "sortablejs";
window.Sortable = Sortable;

// Import Flatpickr
import flatpickr from "flatpickr";
window.flatpickr = flatpickr;
