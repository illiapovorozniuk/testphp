import { FC } from "react";
import { Table } from "react-bootstrap";
import { IBooking } from "../types/IBooking";

interface IProps {
  tableData: IBooking[];
}
const ResultTable: FC<IProps> = ({ tableData }) => {
  return (
    <>
      <table className="table">
        <thead>
          <tr>
            <th scope="col">CarID</th>
            <th scope="col">Busy days</th>
            <th scope="col">Car</th>
            <th scope="col">Created</th>
            <th scope="col">Color</th>
            <th scope="col">Brand</th>
          </tr>
        </thead>
        <tbody>
          {tableData.map((elem) => (
            <tr>
              <th scope="row">{elem.car_id}</th>
              <td>{elem.schedule}</td>
              <td>{elem.car_slug}</td>
              <td>{elem.car_created_at}</td>
              <td>{elem.color}</td>
              <td>{elem.brand_slug}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </>
  );
};

export default ResultTable;
