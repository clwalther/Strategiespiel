window.document.location.search;

async function log_data() {
    const response = await fetch("/api/ministry_labour.php");
    const data = await response.text();
    console.log(data);
  }

log_data();
