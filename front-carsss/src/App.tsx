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
      <div className="d-flex flex-row">
        <FilterForm setTableData={handleSetTableData} />
        <ResultTable tableData={tableData} />
      </div>
    </>
  );
}

export default App;
