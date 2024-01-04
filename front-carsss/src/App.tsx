import "./App.css";

import ResultTable from "./components/ResultTable";
import FilterForm from "./components/FilterForm";
import { useState } from "react";
import { IBooking } from "./types/IBooking";

function App() {
  const [tableData, setTableData] = useState<IBooking[]>([]);
  const handleSetTableData = (newState: IBooking[]) => {
    setTableData(newState);
  };
  return (
    <>
      <div className="container_body">
        <div className="d-flex flex-row filter_booking">
          <FilterForm setTableData={handleSetTableData} />
          <ResultTable tableData={tableData} />
        </div>
      </div>

      <div className="  bg-secondary footer">
        <footer className="text-center text-white ">
          <div className="text-center p-3 bac">© 2023 Strider4ever</div>
        </footer>
      </div>
    </>
  );
}

export default App;
