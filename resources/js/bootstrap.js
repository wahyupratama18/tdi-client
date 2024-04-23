import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Swal from 'sweetalert2';
window.Swal = Swal;

import iziToast from 'izitoast';
window.iziToast = iziToast;

import QrScanner from 'qr-scanner';
window.QrScanner = QrScanner;