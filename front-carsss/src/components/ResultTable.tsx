import { FC, useState } from "react";
import { Button, Table } from "react-bootstrap";
import { IBooking } from "../types/IBooking";

interface IProps {
  tableData: IBooking[];
}
const ResultTable: FC<IProps> = ({ tableData }) => {
  const [currentTablePage, setCurrentTablePage] = useState<number>(1);
  const recordsPerPage = 12;
  const lastIndex = currentTablePage * recordsPerPage;
  const firstIndex = lastIndex - recordsPerPage;
  const bookings = tableData.slice(firstIndex, lastIndex);
  const nPage = Math.ceil(tableData.length / recordsPerPage);
  const numbers = [...Array(nPage + 1).keys()].slice(1);

  const prePage = () => {
    if (currentTablePage !== 1) setCurrentTablePage(currentTablePage - 1);
  };
  const changePage = (id: number) => {
    setCurrentTablePage(id);
  };
  const nextPage = () => {
    if (currentTablePage !== nPage) setCurrentTablePage(currentTablePage + 1);
  };
  return (
    <div>
      <table className="table">
        <thead>
          <tr>
            <th scope="col">CarID</th>
            <th scope="col">Car</th>
            <th scope="col">Сar number</th>
            <th scope="col">Created</th>
            <th scope="col">Color</th>
            <th scope="col">Brand</th>
            <th scope="col">Free days</th>
            <th scope="col">Amount of days</th>
          </tr>
        </thead>
        <tbody>
          {bookings.map((elem) => (
            <tr key={elem.car_id}>
              <th scope="row">{elem.car_id}</th>
              <td>{elem.car_slug}</td>
              <td>{elem.registration_number}</td>
              <td>{elem.car_created_at}</td>
              <td>{elem.color}</td>
              <td>{elem.brand_slug}</td>
              <td>{elem.schedule[0]}</td>
              <td>{elem.schedule[1]}</td>
            </tr>
          ))}
        </tbody>
      </table>

      <div>
        <ul className="pagination d-flex justify-content-center">
          <li className="page-item">
            <a href="#" className="page-link" onClick={prePage}>
              Prev
            </a>
          </li>
          {numbers.map((n, i) => (
            <li
              className={`page-item ${currentTablePage == n ? "active" : ""}`}
              key={i}
            >
              <a href="#" className="page-link" onClick={() => changePage(n)}>
                {n}
              </a>
            </li>
          ))}
          <li className="page-item">
            <a href="#" className="page-link" onClick={nextPage}>
              Next
            </a>
          </li>
        </ul>
      </div>
    </div>
  );
};

export default ResultTable;
