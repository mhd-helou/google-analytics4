const styles = `
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to right, #4db8ff, #33adff);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
}
    .navbar {
    background: #333;
    overflow: hidden;
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
}
    .navbar a {
    float: left;
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}
    .navbar a:hover {
    background-color: #ddd;
    color: black;
}
    h1 {
    color: #fff;
    margin-bottom: 20px;
}
    form {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    width: 300px;
}
    label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    text-align: left; /* Align labels to the left */
}
    input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box; /* Keep content within border */
}
    button[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
    display: block;
    margin: 0 auto; /* Center the button */
}
    button[type="submit"]:hover {
    background-color: #0056b3;
}
    #response {
    display: none; /* Hide result box initially */
    margin-top: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center; /* Center the text in the response box */
    color: #fff;
    background-color: #4CAF50; /* Green background color */
}
`;
export default styles;

