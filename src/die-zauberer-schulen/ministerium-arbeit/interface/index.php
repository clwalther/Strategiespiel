<!DOCTYPE html>
<html>
  <head>
    <title>Ministerium für Arbeit - Interface</title>

    <link rel="stylesheet" href="../../assets/css/start-checkbox.css" />

    <style>
      body {
        font-family: Arial, sans-serif;
        display: block;
        margin: 0;
        padding: 0;
        font-size: 13pt;
      }

      h3 {
        margin-bottom: 10px;
      }

      h4 {
        margin-top: 12px;
        margin-bottom: 10px;
      }

      #left-container {
        width: 18%;
        padding: 10px;
      }

      #team-selection-container {
        background-color: #ebebeb;
        border-radius: 5px;
        padding: 7px;
      }

      .team-item {
        background-color: #d0d0d0;
        border-radius: 5px;
        margin-bottom: 5px;
        padding: 5px;
        cursor: pointer;
      }

      .team-item.active {
        background-color: #333;
        color: #fff;
      }

      #team-details {
        flex-grow: 1;
        padding: 10px;
        padding-top: 25px;
      }

      #team-details table {
        margin-left: 2%;
        margin-right: 2%;
        border-collapse: collapse;
      }

      #job-table th,
      #job-table td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
      }

      .input-cell {
        position: relative;
      }

      .input-field {
        width: 100%;
        box-sizing: border-box;
        padding: 5px;
      }

      .add-influence-button {
        position: absolute;
        top: 50%;
        right: 5px;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #ccc;
        border: none;
        color: #fff;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.15s;
      }

      .graduate-button {
        width: 70%;
        height: 100%;
        background-color: rgb(209, 209, 209);
        font-weight: bold;
        font-size: 15px;
        cursor: pointer;
        transition: background-color 0.15s;
      }

      .add-influence-button:active,
      .graduate-button:active {
        background-color: #747474;
      }

      #bottom-details-container {
        display: flex;
        margin-top: 18px;
        justify-content: space-around;
      }

      #add-labourer-container {
        flex-grow: 0.1;
        margin-right: 10px;
      }

      #prestige-details-container {
        flex-grow: 0.1;
        margin-left: 20px;
      }

      #bottom-details-container table {
        width: 100%;
        border-collapse: collapse;
      }

      #bottom-details-container th,
      #bottom-details-container td {
        padding: 4px;
        text-align: left;
        border-bottom: 1px solid #ddd;
      }

      #add-labourer-container th {
        background-color: #ececec;
      }

      #sabotage-container {
        flex-grow: 0.2;
        margin-left: 20px;
      }

      #add-labourer-container input[type="number"],
      #prestige-details-container input[type="number"] {
        width: 50px;
      }

      #game-start-container {
        display: flex;
        justify-content: space-around;
        margin: 20px;
      }

    </style>

    <?php
		include "../../../scripts/database.php";
    include "../../../scripts/prestige.php";
    include "../../../scripts/influence.php";
    $database = new Database();
    $prestigeDistributer = new PrestigeDistributer();
		$prestigeDistributer->distributePrestigeOfAllJobs();
    $influenceCalculator = new InfluenceCalculator();
    $influenceCalculator->addGraduate(3, "Auror", [3, 4, 5, 6, 7, 8, 9]);

    ?>
  </head>
  <body>
    <div style="display: flex;">
      <!-- Container auf der linken Seite mit der Teamauswahl und Spielstart/Pause/Spielzeit -->
      <div id="left-container">
        <div>
          <h3>Teamauswahl</h3>
          <div id="team-selection-container">
            <div class="team-item">Team 1</div>
            <div class="team-item">Team 2</div>
            <div class="team-item">Team 3</div>
            <div class="team-item">Team 4</div>
            <div class="team-item">Team 5</div>
            <div class="team-item">Team 6</div>
            <div class="team-item">Team 7</div>
            <div class="team-item">Team 8</div>
            <div class="team-item">Team 9</div>
            <div class="team-item">Team 10</div>
            <div class="team-item">Team 11</div>
            <div class="team-item">Team 12</div>
            <div class="team-item">Todesser</div>
            <div class="team-item">Neutral</div>
          </div>
        </div>
      </div>
      <!-- Container auf der rechten Seite mit den Details zum ausgewählten Team -->
      <div id="team-details">
        <!-- Tabelle der Berufsbilder mit Informationen der Teams -->
        <table id="job-table">
          <thead>
            <tr id="nav-row"></tr>
          </thead>
          <tbody></tbody>
        </table>
        <div id="bottom-details-container">
          <!-- Container zum hinzufügen neuer Arbeitnehmer -->
          <div id="add-labourer-container">
            <h3>Neuer Arbeitnehmer</h3>
            <table id="new-labourer-table">
              <thead>
                <tr>
                  <th>Eigenschaft</th>
                  <th>Wert</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <!-- Container mit Prestige und Platzierungen der Teams / dessen Orden-->
          <div id="prestige-details-container">
            <h3>Team</h3>
            <table>
              <tr>
                <td>Prestige:</td>
                <td id="team-prestige">999</td>
              </tr>
              <tr>
                <td>Platz im Orden:</td>
                <td id="team-place-in-order">999</td>
              </tr>
              <tr>
                <td>Platz im Spiel:</td>
                <td id="team-place-overall">999</td>
              </tr>
            </table>
            <h3>Orden</h3>
            <h4>Ordenbezeichner</h4>
            <table>
              <tr>
                <td>Prestige:</td>
                <td id="order-prestige">999</td>
              </tr>
              <tr>
                <td>Platz im Spiel:</td>
                <td id="order-place">999</td>
              </tr>
            </table>
          </div>
          <!-- Container mit Sabotageoptionen für/gegen das Ausgewählete Team-->
          <div id="sabotage-container">
            <h3>Sabotage</h3>
            <table>
              <tr>
                <td>Einfluss verdecken:</td>
                <td>0min</td>
                <td><input type="number" min="0" max="999" value="0" /></td>
                <td><button id="freeze-prestige-button">V</button></td>
              </tr>
              <tr>
                <td>Prestige verdecken:</td>
                <td>0min</td>
                <td><input type="number" min="0" max="999" value="0" /></td>
                <td><button id="freeze-prestige-button">V</button></td>
              </tr>
              <tr>
                <td>Prestige verdecken:</td>
                <td>0min</td>
                <td><input type="number" min="0" max="999" value="0" /></td>
                <td><button id="freeze-prestige-button">V</button></td>
              </tr>
              <tr>
                <td>Einfluss multiplikator:</td>
                <td>1.0</td>
                <td><input type="number" min="0" max="999" value="0" /></td>
                <td><button id="freeze-prestige-button">V</button></td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <script>
        var teamItems = document.querySelectorAll(".team-item");
        var jobArray = [
          "Job 1",
          "Job 2",
          "Job 3",
          "Job 4",
          "Job 5",
          "Job 6",
          "Job 7",
        ];
        var influenceArray = [10234, 234, 1213, 3334, 234, 23, 10];
        var influenceShareArray = [40, 7, 8, 9, 10, 3, 1];
        var influencePlacementArray = [1, 3, 5, 2, 12, 12, 12];
        var skillArray = [
          "Zauberkunst",
          "Verteidigung gddK.",
          "Geschichte d. Zauberei",
          "Zaubertränke",
          "Kräuterkunde",
          "Pflege m. Geschöpfe",
          "Besenfliegen",
        ];

        // Fill the table nav with jobArray values
        var navRow = document.getElementById("nav-row");
        jobArray.forEach(function (job) {
          var th = document.createElement("th");
          th.textContent = job;
          navRow.appendChild(th);
        });

        teamItems.forEach(function (item) {
          item.addEventListener("click", function () {
            teamItems.forEach(function (item) {
              item.classList.remove("active");
            });
            this.classList.add("active");
            displayTeamDetails(this.textContent);
          });
        });

        teamItems[0].classList.add("active");
        displayTeamDetails(teamItems[0].textContent);

        // Display the details of the selected team on the right side
        function displayTeamDetails(team) {
          var tableBody = document.querySelector("#job-table tbody");
          tableBody.innerHTML = "";

          var row1 = document.createElement("tr");
          influencePlacementArray.forEach(function (value) {
            var td = document.createElement("td");
            td.textContent = value;
            row1.appendChild(td);
          });
          tableBody.appendChild(row1);

          var row2 = document.createElement("tr");
          influenceShareArray.forEach(function (value) {
            var td = document.createElement("td");
            td.textContent = value + "%";
            row2.appendChild(td);
          });
          tableBody.appendChild(row2);

          var row3 = document.createElement("tr");
          influenceArray.forEach(function (value) {
            var td = document.createElement("td");
            td.textContent = value;
            row3.appendChild(td);
          });
          tableBody.appendChild(row3);

          var row4 = document.createElement("tr");
          influenceArray.forEach(function () {
            var td = document.createElement("td");
            var inputCell = document.createElement("div");
            inputCell.classList.add("input-cell");
            var inputField = document.createElement("input");
            inputField.type = "text";
            inputField.classList.add("input-field");
            var addButton = document.createElement("button");
            addButton.classList.add("add-influence-button");
            addButton.textContent = "+";
            addButton.addEventListener("click", function () {
              inputField.value = "";
            });
            inputCell.appendChild(inputField);
            inputCell.appendChild(addButton);
            td.appendChild(inputCell);
            row4.appendChild(td);
          });
          tableBody.appendChild(row4);

          var row5 = document.createElement("tr");
          influenceArray.forEach(function () {
            var td = document.createElement("td");
            var graduateButton = document.createElement("button");
            graduateButton.classList.add("graduate-button");
            //    graduateButton.classList.add("button");
            graduateButton.textContent = Math.floor(
              Math.random() * 10
            ).toString();
            td.appendChild(graduateButton);
            row5.appendChild(td);
          });
          tableBody.appendChild(row5);
        }

        function updateTeamDetails() {

        }

        function updateGraduateButtons() {
          var graduateButtons = document.querySelectorAll(
            ".graduate-button"
          );
          graduateButtons.forEach(function (button) {
            button.textContent = Math.floor(Math.random() * 10).toString();
          });
        }

        var newLabourerTableBody = document.querySelector(
          "#new-labourer-table tbody"
        );
        var skillColumn = document.createElement("td");
        skillArray.forEach(function (value) {
          var row = document.createElement("tr");
          var eigenschaftCell = document.createElement("td");
          eigenschaftCell.textContent = value;
          row.appendChild(eigenschaftCell);
          var wertCell = document.createElement("td");
          var input = document.createElement("input");
          input.type = "number";
          input.min = "0";
          input.max = "7";
          input.value = 0;
          input.pattern = "[0-7]";
          wertCell.appendChild(input);
          row.appendChild(wertCell);

          newLabourerTableBody.appendChild(row);
        });
      </script>
    </div>

    <div id="game-start-container">
      <div>
        Spielzeit: <span id="game-time">0:00:00</span>
      <div class="checkbox-wrapper-10">
        <input checked="" type="checkbox" id="cb5" class="tgl tgl-flip" />
        <label
          for="cb5"
          data-tg-on="läuft"
          data-tg-off="pausiert"
          class="tgl-btn"
        ></label>
      </div>
      </div>
    </div>
  </body>
</html>