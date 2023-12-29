import { FC } from "react";
import { Table } from "react-bootstrap";
import { IBooking } from "../types/IBooking";

interface IProps {
  tableData: IBooking[];
}
const ResultTable: FC<IProps> = ({ tableData }) => {
  // const arr = [1, 2, 3, 4];
  return (
    <>
      <Table>
        {tableData.map((booking) => (
          <tr>
            <td className="table-primary">Hello there {booking.source}</td>
          </tr>
        ))}
      </Table>
    </>
  );
};

export default ResultTable;
