document.addEventListener("DOMContentLoaded", () => {
    const ts_open = document.getElementById("ts_open");
    const ts_closed = document.getElementById("ts_closed");
    const ts_all = document.getElementById("ts_all");
    const urlParams = new URLSearchParams(window.location.search);
  
    // Példa: ha az URL-ben ?color=red van
    const status = urlParams.get("status");
  
    if (status=="open") {
      ts_open.classList.add("selected_button");
      ts_closed.classList.add("default_button");
      ts_all.classList.add("default_button");
    }
    
    if (status=="closed") {
      ts_open.classList.add("default_button");
      ts_closed.classList.add("selected_button");
      ts_all.classList.add("default_button");
    }

    if (status=="all") {
      ts_open.classList.add("default_button");
      ts_closed.classList.add("default_button");
      ts_all.classList.add("selected_button");
    }

  });
  