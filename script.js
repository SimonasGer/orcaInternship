document.addEventListener("DOMContentLoaded", () => {
    get()
})

const get = () => {
    let xhr = new XMLHttpRequest()
    xhr.open("GET", "get.php", true)
    xhr.onload = () => {
        console.log("Raw Response:", xhr.response);
        try {
            let response = JSON.parse(xhr.response);
            console.log("Parsed JSON:", response);
        } catch (error) {
            console.error("JSON Parse Error:", error, "Response was:", xhr.response);
        }
        if (xhr.status == 200) {
            let comments = JSON.parse(xhr.response)
            let htmlComment = ""
            comments.forEach(comment => {
                const replies = () => {
                    let htmlReply = ""
                    comment.replies.forEach(reply => {
                        htmlReply +=
                        `
                        <article class="reply">
                            <div class="top">
                                <p>${reply.name}</p>
                                <p>${reply.created_at}</p>
                            </div>
                            <div class="bottom">
                                <p>${reply.content}</p>
                            </div>
                        </article>
                        `
                    })
                    return htmlReply
                };

                htmlComment += 
                `
                <article class="comment">
                    <div class="top">
                        <p>${comment.name}</p>
                        <p>${comment.created_at}</p>
                    </div>
                    <div class="bottom">
                        <p>${comment.content}</p>
                        <details>
                            <summary><p>Reply</p></summary>
                            <form class="reply-form" data-parent-id="${comment.id}">
                                <fieldset>
                                    <input class="replyEmail" placeholder="Email">
                                    <input class="replyName" placeholder="Name">
                                    <textarea class="replyContent" placeholder="Comment"></textarea>
                                </fieldset>
                                <div class="replyError"></div>
                                <button type="submit">Submit</button>
                            </form>
                        </details>
                    </div>
                    <section class="replies">
                        ${replies()}
                    </section>
                </article>
                `
            })

            document.querySelector(".comments").innerHTML = htmlComment;

            // Attach event listeners after loading comments
            document.querySelectorAll(".reply-form").forEach(form => {
                form.addEventListener("submit", (e) => {
                    e.preventDefault()
                    postReply(e.target)
                })
            })

            document.querySelector(".post").addEventListener("submit", (e) => {
                e.preventDefault()
                post("post")
            })
        }
    }
    xhr.send()
}

const post = (formClass) => {
    let form = document.querySelector(`.${formClass}`)
    if (!form) return

    let name = form.querySelector(`.${formClass}Name`).value
    let email = form.querySelector(`.${formClass}Email`).value
    let content = form.querySelector(`.${formClass}Content`).value
    let errorBox = document.querySelector(`.${formClass}Error`)

    let formData = new FormData()
    formData.append("name", name)
    formData.append("email", email)
    formData.append("content", content)

    let xhr = new XMLHttpRequest()
    xhr.open("POST", "post.php", true)
    xhr.onload = () => {
        console.log("Raw Response:", xhr.response);
        try {
            let response = JSON.parse(xhr.response);
            console.log("Parsed JSON:", response);
        } catch (error) {
            console.error("JSON Parse Error:", error, "Response was:", xhr.response);
        }
        if (xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            if(response.status == "success"){
                form.querySelector(`.${formClass}Name`).value = ""
                form.querySelector(`.${formClass}Email`).value = ""
                form.querySelector(`.${formClass}Content`).value = ""
                errorBox.innerHTML = ""
                get() // Refresh comments
            } else {
                errorBox.innerHTML = response.message
            }
        }
    }
    xhr.send(formData)
}

const postReply = (form) => {
    let name = form.querySelector(".replyName").value
    let email = form.querySelector(".replyEmail").value
    let content = form.querySelector(".replyContent").value
    let parent_id = form.getAttribute("data-parent-id")
    let errorBox = document.querySelector(`.replyError`)

    let formData = new FormData()
    formData.append("name", name)
    formData.append("email", email)
    formData.append("content", content)
    formData.append("parent_id", parent_id)

    let xhr = new XMLHttpRequest()
    xhr.open("POST", "post.php", true)
    xhr.onload = () => {
        if (xhr.status == 200) {
            console.log("Raw Response:", xhr.response);
            try {
                let response = JSON.parse(xhr.response);
                console.log("Parsed JSON:", response);
            } catch (error) {
                console.error("JSON Parse Error:", error, "Response was:", xhr.response);
            }
            let response = JSON.parse(xhr.responseText);
            if(response.status == "success"){
                form.querySelector(".replyName").value = ""
                form.querySelector(".replyEmail").value = ""
                form.querySelector(".replyContent").value = ""
                get(); // Refresh
            } else {
                errorBox.innerHTML = response.message
            }
        }
    }
    xhr.send(formData)
}
