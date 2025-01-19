document.addEventListener("DOMContentLoaded", () => {
    const ts_open = document.getElementById("ts_open");
    const ts_closed = document.getElementById("ts_closed");
    const ts_all = document.getElementById("ts_all");
    const urlParams = new URLSearchParams(window.location.search);

    const post_controll = document.getElementById("pc_button");
  
    
    const status = urlParams.get("status");
  
    if (status=="open") {
      ts_open.classList.add("c2_b");
      ts_closed.classList.add("b1_b");
      ts_all.classList.add("b1_b");
    }
    
    if (status=="closed") {
      ts_open.classList.add("b1_b");
      ts_closed.classList.add("c2_b");
      ts_all.classList.add("b1_b");
    }

    if (status=="all") {
      ts_open.classList.add("b1_b");
      ts_closed.classList.add("b1_b");
      ts_all.classList.add("c2_b");
    }

  });
  