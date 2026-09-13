<style>
  
    * {
        box-sizing: border-box;
    }

    /* Button used to open the chat form - fixed at the bottom of the page */
    .open-button {
      background-color: black;
        padding: 16px 20px;
        border-radius: 10px;
        cursor: pointer;
        opacity: 0.8;
        position: fixed;
        bottom: 23px;
        right: 28px;
        width: 200px;
        color: #04AA6D;
        font-size: large;

    }

    /* The popup chat - hidden by default */
    .chat-popup {
        display: none;
        position: fixed;
        bottom: 0;
        right: 15px;
        /* border: 3px solid #f1f1f1; */
        z-index: 9;
    }

    /* Add styles to the form container */
    .form-container {
        max-width: 300px;
        padding: 10px;
        background-color: white;
    }

    /* Full-width textarea */
    .form-container textarea {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        border: none;
        background: #f1f1f1;
        resize: none;
        min-height: 200px;
    }

    /* When the textarea gets focus, do something */
    .form-container textarea:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Set a style for the submit/send button */
    .form-container .btn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-bottom: 10px;
        opacity: 0.8;
    }

    /* Add a red background color to the cancel button */
    .form-container .cancel {
        background-color: red;
    }

    /* Add some hover effects to buttons */
    .form-container .btn:hover,
    .open-button:hover {
        opacity: 1;
    }
</style>

<button class="open-button" style="background-image: url(assets/chat.svg);" onclick="openForm()">Ask Question</button>

<div class="chat-popup" id="myForm">
    <form action="/action_page.php" class="form-container">

        

        <h3>New Online Optics!</h3>

        <div class="form-group">
            <label for="">Phone Number or  Email </label>
            <input type="text" placeholder="Enter your phone number here!" class="form-control" name="cust_phone"
                value="">
        </div>
         <div class="form-group">
             <label for="">Message/Question</label>
             <textarea placeholder="Type message.." name="msg" required></textarea>
         </div>



       
        

        <button type="submit" class="btn">Send</button>
        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
    </form>
</div>





<script>
    function openForm() {
        document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
    }
</script>