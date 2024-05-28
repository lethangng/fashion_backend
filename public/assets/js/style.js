function showLoading() {
    Swal.fire({
        title: "Loading...",
        html: "Vui lòng chờ đợi!",
        timer: false,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
        willClose: () => {
            // clearInterval(timerInterval);
        }
    }).then((result) => {
        /* Read more about handling dismissals below */
        // if (result.dismiss === Swal.DismissReason.timer) {
        //     console.log("I was closed by the timer");
        // }
    });
}