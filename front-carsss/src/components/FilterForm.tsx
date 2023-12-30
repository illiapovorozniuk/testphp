import { FC, useState, useEffect } from "react";
import { Form } from "react-bootstrap";
import { IBooking, ISource } from "../types/IBooking";
import axios from "axios";

interface IProps {
  setTableData: (state: IBooking[]) => void;
}

const FilterForm: FC<IProps> = ({ setTableData }) => {
  const [requestBody, setRequestBody] = useState<IBooking>({
    booking_id: 1,
    car_id: 1,
    source: "carsss",
  });
  const [years, setYears] = useState<{ year: number }[]>([]);

  const [booking_id, setBooking_id] = useState<number>(1);
  const [year, setYear] = useState<number>();
  const [car_id, setCar_id] = useState<number>(1);
  const [source, setSource] = useState<"carsss" | "direct" | "other">("carsss");

  useEffect(() => {
    getFilterBooking(requestBody);
    setYearsHandle();
  }, []);

  const handleChange = () => {
    setRequestBody({
      booking_id: booking_id,
      car_id: car_id,
      source: source,
    });
  };
  useEffect(() => {
    handleChange();
  }, [booking_id, car_id, source]);

  const getFilterBooking = async (requestBody: IBooking) => {
    try {
      fetch("http://localhost:8000/api/filterbooking", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(requestBody),
      })
        .then((response) => response.json())
        .then((data: IBooking[]) => {
          console.log(data[0]);
          setTableData(data);
        });
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  };
  const setYearsHandle = () => {
    axios.post("http://localhost:8000/api/getYears").then((response) => {
      setYears(response.data);
    });
  };

  return (
    <form className="m-3">
      <div className="row">
        <div className="col">
          <label className="form-label">Year *</label>
          <select
            className="form-select "
            value={year}
            onChange={(e) => {
              // setBooking_id(Number(e.target.value));
              // getFilterBooking({
              //   ...requestBody,
              //   booking_id: Number(e.target.value),
              // });
              setYear(Number(e.target.value));
            }}
          >
            {years.map((year, i) => (
              <option value={year.year}>{year.year}</option>
            ))}
          </select>
        </div>
        <div className="col">
          <label className="form-label">Month</label>
          <select
            className="form-select form-select-sm"
            value={booking_id}
            onChange={(e) => {
              setBooking_id(Number(e.target.value));
              getFilterBooking({
                ...requestBody,
                booking_id: Number(e.target.value),
              });
            }}
          >
            {/* <option>Select an option</option>
            {years.map((year) => (
              <option value={year}>{year}</option>
            ))} */}
          </select>
        </div>
      </div>
    </form>
  );
};

export default FilterForm;
