document.querySelector("#searchIPs").addEventListener(
    "keyup",
    (e) => {
        let itesmFound = [];


        document.querySelectorAll(".name-wrap #users").forEach((el) => {
            let user1 = document.querySelectorAll(".user_1")
            let user2 = document.querySelectorAll(".user_2")
            //console.log only results that match the search also trigger if one letter is found.
            if (el.innerHTML.toLowerCase().indexOf(e.target.value.toLowerCase()) > -1) {
                itesmFound.push(true);
                document.querySelector("#noUser").textContent = "";


            } else {
                //Say no user was found with that name if no strings match
                el.style.display = 'none';
                itesmFound.push(false);
            }

            //reset once e is empty
            if (e.target.value == "") {
                el.style.display = "";
            }

            if (!itesmFound.includes(true)) {
                document.querySelector("#noUser").textContent =
                    'No user found with that name. \n\nPress enter to reset searh.';
            }
        });



        { passive: true }
    }
);