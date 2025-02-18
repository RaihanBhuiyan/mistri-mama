import Echo from 'laravel-echo'

let token = document.head.querySelector('meta[name="csrf-token"]').content;
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 6001,
    disableStats: true,
    auth: {
        headers: {
            // Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImI5NDM5NWYwOTU2OTQwMDQyNzlhMjQyYmNhYmE5OTVjOTI4MmJlMDRlYzBiMmQ5NjI1OGYzMWUxMjhjZGY4MjQxZDQyMzFiYjllY2IzYTcxIn0.eyJhdWQiOiIyIiwianRpIjoiYjk0Mzk1ZjA5NTY5NDAwNDI3OWEyNDJiY2FiYTk5NWM5MjgyYmUwNGVjMGIyZDk2MjU4ZjMxZTEyOGNkZjgyNDFkNDIzMWJiOWVjYjNhNzEiLCJpYXQiOjE1NzYwMzYwNzUsIm5iZiI6MTU3NjAzNjA3NSwiZXhwIjoxNjA3NjU4NDc1LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.TUMUvbxLGIby5spHVHNoR4lwmh7ETlTM2qu5_qDqEeCNJmLEUVmHq6fq1jFu0RebHh4JZTNCPkwf8a7L1s1l-uExOkfGjdg71l2wKLncIv60pdD8hAhaHIzzEjKvRdaTO06XRhklwOjw3h6_54Vq86Vx8zlVmCQrLVzqDxTcBpJR2lQS7TIUSzrr2P2jgCOgu7EQ6C2GiLhnaB5UJn6u9fcmXmZ5To6bECEYZKQrRPTse8uolBvbS5yjrvUkFuAk9XHiHCmx1jqkeQbyzs8hyBwF9pOrkTmlreW5qKgFWfuLDmIu2ZuKHDtS0CwtJTBR2HBJsJOTnnjewYk0iNCnzCyruV_dQoNyFp4pHYuPVEpnHzDAq1HEGhXGxVsqNT4oSfaJZtjhGxxmZq8Ec7YLrh-rn_UBzkJcg6IKu9whWnV8Bz6lWkDeAUZGtBZckUPIFOqoPmpDQNpVolXheGiawI_YaUs0CcET42YxvfYd4qhkI6h3bcBA0N_isBSP07VSohisV1bevDMI2XFmGrjy88DR5N9kz8ACtx4C5bc6SijWGyhAotzE_WqaOZ5owYfEQ1pa5aUeYjxy9WUFg5TtiMkhzCMMgefa9tGsX-6BywTwD7hlVSkKS1-B8IgGXIxWFle7NdwfMdBVVxjdfxfHPCUSgbwWsCJZ0cSuDet-Ga4'
            Authorization: 'Bearer ' + document.head.querySelector('meta[name="csrf-token"]').content
        }
    }
});
userId = document.getElementById('BuserId').value;
window.Echo.private("App.User." + userId).notification(
    notification => {
        console.log(notification);
    });
