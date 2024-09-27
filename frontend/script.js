document.getElementById("crawlBtn").addEventListener("click", async () => {
    const apiKey = "your_api_key"; // Same key as in api.php
    const response = await fetch(`http://localhost/lumav-proov/backend/api.php?api_key=${apiKey}`);

    if (response.ok) {
        const data = await response.json();
        displayResults(data);
    } else {
        console.error("Error fetching data:", response.status);
    }
});

function displayResults(data) {
    const resultsDiv = document.getElementById("results");
    resultsDiv.innerHTML = '';

    for (const [url, result] of Object.entries(data)) {
        const urlDiv = document.createElement("div");
        urlDiv.innerHTML = `<h2>${url}</h2><p>Title: ${result.title}</p><ul>${result.products.map(product => `<li>${product}</li>`).join('')}</ul>`;
        resultsDiv.appendChild(urlDiv);
    }
}
